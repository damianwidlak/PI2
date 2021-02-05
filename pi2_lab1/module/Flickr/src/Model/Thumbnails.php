<?php

namespace Flickr\Model;

class Thumbnails
{
  private $flickr;

  public function __construct()
  {
  }

  public function search($phrase = 'python', $page = 1)
  {
    $client = new \Laminas\Http\Client();
    $query = 'https://api.flickr.com/services/rest/?format=json&nojsoncallback=1&method=flickr.photos.search&api_key=d50f1948e211129da06068b52266b5c0&per_page=10&page=%s&text=%s';
    $query_url = sprintf($query, $page, urlencode($phrase));

    $client->setUri($query_url);


    $response = $client->send();
    // $photos = \Zend\Json\Json::decode($response->getBody());
    $photos = json_decode($response->getBody());
    return $photos;
  }

  public function getDetails($id)
  {
    $client = new \Laminas\Http\Client();
    $query = 'https://api.flickr.com/services/rest/?format=json&nojsoncallback=1&method=flickr.photos.getInfo&api_key=d50f1948e211129da06068b52266b5c0&photo_id=%s';
    $query_url = sprintf($query, $id);

    $client->setUri($query_url);

    $response = $client->send();
    // $photos = \Zend\Json\Json::decode($response->getBody());
    $photos = json_decode($response->getBody());
    return $photos;
  }

  public function getUser($id, $page = 1)
  {
    $client = new \Laminas\Http\Client();
    $query = 'https://api.flickr.com/services/rest/?format=json&nojsoncallback=1&method=flickr.people.getPhotos&api_key=d50f1948e211129da06068b52266b5c0&per_page=10&page=%s&user_id=%s';
    $query_url = sprintf($query, $page, urlencode($id));

    $client->setUri($query_url);
    // dd($query_url);
    $response = $client->send();
    // $photos = \Zend\Json\Json::decode($response->getBody());
    $userPhotos = json_decode($response->getBody());
    $userPhotos->PI2_userID = $id;
    return $userPhotos;
  }
}
