<?php


namespace ApiKlient\Controller;


use ApiKlient\Form\NieruchomoscForm;
use Interop\Container\ContainerInterface;
use Laminas\Soap\Client;

class IndexControllerFactory implements \Laminas\ServiceManager\Factory\FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        // $klient = new Client('http://localhost/pi2/public/api-serwer/wsdl'); // xampp
        $klient = new Client('http://web/api-serwer/wsdl'); // docker
        $form = $container->get(NieruchomoscForm::class);
        $klucz = $container->get('config')['klucz_api'];

        return new IndexController($form, $klient, $klucz);
    }
}