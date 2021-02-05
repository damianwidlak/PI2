<?php

namespace Dropbox\Service;

use Laminas\Http\Client;
use Laminas\Json\Json;
use Laminas\Session\Container;

class Dropbox
{
    const CONTENT_URL = 'https://content.dropboxapi.com/2/';
    const API_URL = 'https://api.dropboxapi.com/2/';
    // const REDIRECT_URI = 'http://localhost/pi2/public/dropbox/finish'; // xampp
    const REDIRECT_URI = 'http://localhost:8080/dropbox/finish'; // docker

    private Container $container;
    private array $config;

    public function __construct(array $config)
    {
        $this->container = new Container();
        $this->config = $config['dropbox'];
    }

    public function generateAuthorizationUrl(): string
    {
        return sprintf(
            "https://www.dropbox.com/oauth2/authorize?client_id=%s&redirect_uri=%s&response_type=code",
            $this->config['key'],
            self::REDIRECT_URI
        );
    }

    public function authorized(): bool
    {
        return isset($this->container->access_token);
    }

    public function getAccessToken($authorizationCode)
    {
        $client = new Client('https://api.dropboxapi.com/oauth2/token');
        $client->setMethod('post');
        $client->setParameterPost([
            'code' => $authorizationCode,
            'grant_type' => 'authorization_code',
            'client_id' => $this->config['key'],
            'client_secret' => $this->config['secret'],
            'redirect_uri' => self::REDIRECT_URI,
        ]);

        try {
            $response = $client->send();

            if ($response->isSuccess()) {
                $data = Json::decode($response->getBody());

                if (!empty($data->access_token)) {
                    $this->container->access_token = $data->access_token;
                    return true;
                }
            }

            return "Wystąpił błąd: " . $response->getBody();
        } catch (\Exception $e) {
            return "Wystąpił błąd: " . $e->getMessage();
        }
    }

    public function getFileList($path)
    {
        try {
            $files = $this->sendRequest('/files/list_folder', ['path' => $path]);

            return $files->entries;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function deleteFile($path) {
        if (strlen($path)>1 ) {
            try {
                $files = $this->sendRequest('/files/delete_v2', ['path' => $path]);
                return $files->entries;
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        }
        return false;
    }

    public function uploadFile($filepath, $content) {
        $client = new Client(self::CONTENT_URL . 'files/upload');
        $client->setMethod('post');
        $client->setHeaders([
            'Authorization' => 'Bearer ' . $this->container->access_token,
            'Content-Type' => 'application/octet-stream',
            'Dropbox-API-Arg' => Json::encode([
                "path" => $filepath,
                "mode" => "overwrite",
                "autorename" => false,
            ]),
        ]);

        $client->setRawBody($content);
        $resp = $client->send();
        return $resp;
    }
    public function downloadFile($path) {
        $client = new Client(self::CONTENT_URL . 'files/download');
        $client->setMethod('get');
        $client->setHeaders([
           'Authorization' => 'Bearer ' . $this->container->access_token,
           'Dropbox-API-Arg' => '{"path": "'. $path . '"}',
        ]);

        $client->setRawBody(Json::encode(['path' => $path]));
        $response = $client->send();
        return $response;
    }

    public function createFolder($path) {
        if (strlen($path)>1 ) {
            try {
                $result = $this->sendRequest('/files/create_folder_v2', ['path' => $path]);
                return $result->entries;
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        }
        return false;
    }
    private function sendRequest($function, $parameters = [])
    {
        $client = new Client(self::API_URL . $function);
        $client->setMethod('post');
        $client->setHeaders([
            'Authorization' => 'Bearer ' . $this->container->access_token,
            'Content-Type' => 'application/json'
        ]);
        $client->setRawBody(Json::encode($parameters));

        $response = $client->send();

        if ($response->isSuccess()) {
            return Json::decode($response->getBody());
        } else {
            throw new \Exception($response->getContent());
        }
    }


}
