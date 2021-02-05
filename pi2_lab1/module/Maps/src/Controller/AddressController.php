<?php

namespace Maps\Controller;

// use Application\Form\AddressForm;
use Maps\Model\Address;
use Maps\Form\AddressForm;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class AddressController extends AbstractActionController
{
    /**
     * @var Address
     */
    private $address;

    /**
     * @var AddressForm
     */
    private $addressForm;

    public function __construct(Address $address, AddressForm $addressForm)
    {
        $this->address = $address;
        $this->addressForm = $addressForm;
    }

    public function indexAction()
    {
        

        $this->addressForm->get('save')->setValue('Dodaj');
        $request = $this->getRequest();
        if ($request->isPost()) {
            $this->addressForm->setData($request->getPost());

            if ($this->addressForm->isValid()) {
                $this->address->add($request->getPost());

                return $this->redirect()->toRoute('maps');
            }
        }

        return new ViewModel([
            'addresses' => $this->address->listAll(),
            'form' => $this->addressForm
        ]);
    }

    public function addAction()
    {
        $this->addressForm->get('save')->setValue('Dodaj');
        $request = $this->getRequest();
        if ($request->isPost()) {
            $this->addressForm->setData($request->getPost());

            if ($this->addressForm->isValid()) {
                $this->address->add($request->getPost());

                return $this->redirect()->toRoute('maps');
            }
        }

        return new ViewModel(['title' => 'Adding new address', 'form' => $this->addressForm]);
    }

 
}
