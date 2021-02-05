<?php

namespace Maps\Form;

use Maps\Model\Address;
use Laminas\Form\Form;
use Laminas\InputFilter\InputFilterProviderInterface;

class AddressForm extends Form implements InputFilterProviderInterface
{
  public function __construct(Address $address)
  {
    parent::__construct('address');

    $this->setAttributes(['method' => 'post', 'class' => 'form']);
    $this->add([
      'name' => 'address',
      'type' => 'Text',
      'options' => [
        'label' => 'Adres',
      ],
      'attributes' => ['class' => 'form-control'],
    ]);

    $this->add([
      'name' => 'details',
      'type' => 'Text',
      'options' => [
        'label' => 'Szczegóły adresu',
      ],
      'attributes' => ['class' => 'form-control'],
    ]);

    $this->add([
      'name' => 'color',
      'type' => 'Select',
      'options' => [
        'label' => 'Kolor znacznika',
        'value_options' => [
          'yellow' => 'Żółty',
          'blue' => 'Niebieski',
          'pink' => 'Różowy',
          'green' => 'Zielony',
          'ltblue' => 'Błękitny',
          'orange' => 'Pomarańczowy',
          'red' => 'Czerwony'
        ],
      ],
      'attributes' => ['class' => 'form-control'],
    ]);

    $this->add([
      'name' => 'save',
      'type' => 'Submit',
      'attributes' => [
        'value' => 'Zapisz',
        'class' => 'btn btn-primary',
      ],
    ]);
  }

  public function getInputFilterSpecification()
  {
    return [
      [
        'name' => 'address',
        'required' => true,
        'filters' => [
          ['name' => 'StripTags'],
          ['name' => 'StringTrim'],
        ],
        'validators' => [],
      ],
      [
        'name' => 'details',
        'required' => true,
        'filters' => [
          ['name' => 'StripTags'],
          ['name' => 'StringTrim'],
        ],
        'validators' => [],
      ]
    ];
  }
}
