<?php

namespace ApiKlient\Controller;

use ApiKlient\Form\NieruchomoscForm;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Soap\Client;

class IndexController extends AbstractActionController
{
    private NieruchomoscForm $nieruchomoscForm;
    private Client $klient;
    private string $klucz;

    /**
     * IndexController constructor.
     *
     * @param NieruchomoscForm $nieruchomoscForm
     * @param Client           $klient
     * @param string           $klucz
     */
    public function __construct(NieruchomoscForm $nieruchomoscForm, Client $klient, string $klucz)
    {
        $this->nieruchomoscForm = $nieruchomoscForm;
        $this->klient = $klient;
        $this->klucz = $klucz;
    }

    public function indexAction()
    {
        $request = $this->getRequest();
        $id = $this->params()->fromRoute('id');
        $view = [];

        $this->nieruchomoscForm->get('id_miasto')->setValueOptions($this->klient->pobierzMiasta($this->klucz));

        if ($request->isPost()) {
            $dane = $request->getPost();
            $this->nieruchomoscForm->setData($dane);
            if ($this->nieruchomoscForm->isValid()) {
                if ($id) {
                    // modyfikuj
                    try {
                        $this->klient->aktualizuj($this->klucz, $id, $this->nieruchomoscForm->getData());
                        $view['komunikat']['success'] = "Nieruchomość została zmieniona.";
                    } catch (\SoapFault $e) {
                        $view['komunikat']['danger'] = $e->getMessage();
                    }
                } else {
                    // dodaj
                    try {
                        $id_new = $this->klient->dodaj($this->klucz, $this->nieruchomoscForm->getData());
                        $view['komunikat']['success'] = "Nieruchomość została dodana (<strong>ID: $id_new</strong>).";
                    } catch (\SoapFault $e) {
                        $view['komunikat']['danger'] = $e->getMessage();
                    }
                }
            } else {
                $view['komunikat']['danger'] = 'Niepoprawny formularz';
            }
        } else {
            if ($id) {
                $this->nieruchomoscForm->setData($this->klient->pobierzJeden($this->klucz, $id));
                $this->nieruchomoscForm->get('dodaj')->setLabel('Zmień');
            }
        }

        $view['form'] = $this->nieruchomoscForm;

        return $view;
    }

    public function usunAction() {
        $id = $this->params()->fromRoute('id');
        $this->klient->usun($this->klucz, $id);
        return $this->redirect()->toRoute('api-klient/akcje', ['action' => 'lista']);
    }
    public function listaAction()
    {
        return [
            'dane' => $this->klient->pobierzWszystko($this->klucz),
            'miasta' => $this->klient->pobierzMiasta($this->klucz),
        ];
    }
}