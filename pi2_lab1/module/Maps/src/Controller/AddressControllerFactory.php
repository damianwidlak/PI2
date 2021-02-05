<?php

namespace Maps\Controller;

use Maps\Form\AddressForm;
use Maps\Model\Address;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class AddressControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container,  $requestedName, array $options = null)
    {
        $address = $container->get(Address::class);
        $addressForm = $container->get(AddressForm::class);

        return new AddressController($address, $addressForm);
    }
}
