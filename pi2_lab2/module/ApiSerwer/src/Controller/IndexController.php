<?php

namespace ApiSerwer\Controller;

use ApiSerwer\Model\Nieruchomosci;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Soap;

ini_set("soap.wsdl_cache_enabled", 0);

class IndexController extends AbstractActionController
{
    private Nieruchomosci $nieruchomosci;

    /**
     * IndexController constructor.
     *
     * @param Nieruchomosci $nieruchomosci
     */
    public function __construct(Nieruchomosci $nieruchomosci)
    {
        $this->nieruchomosci = $nieruchomosci;
    }

    public function indexAction()
    {
        // $serwer = new Soap\Server('http://localhost/pi2/public/api-serwer/wsdl'); // xampp
        $serwer = new Soap\Server('http://web/api-serwer/wsdl'); // docker
        $serwer->setObject($this->nieruchomosci);
        $serwer->handle();

        return $this->getResponse();
    }

    public function wsdlAction()
    {
        $autodiscover = new Soap\AutoDiscover();
        $autodiscover->setClass(Nieruchomosci::class);
        // $autodiscover->setUri('http://localhost/pi2/public/api-serwer'); // xampp
        $autodiscover->setUri('http://web/api-serwer'); // docker
        $autodiscover->handle();

        return $this->getResponse();
    }
}
