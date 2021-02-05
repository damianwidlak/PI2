<?php

namespace ApiKlient\Form;

use Laminas\Form\Form;
use Laminas\I18n\Validator\IsFloat;
use Laminas\I18n\Validator\IsInt;
use Laminas\InputFilter\InputFilterProviderInterface;
use Laminas\Validator\Digits;

class NieruchomoscForm extends Form implements InputFilterProviderInterface
{
    public function __construct()
    {
        parent::__construct();

        $this->add([
            'type' => 'radio',
            'name' => 'typ_nieruchomosci',
            'options' => [
                'layout' => 'inline',
                'form_group' => false,
                'value_options' => [
                    'mieszkanie' => 'Mieszkanie',
                    'dom' => 'Dom',
                    'działka' => 'Działka',
                ],
            ],
        ]);
        $this->add([
            'type' => 'radio',
            'name' => 'typ_oferty',
            'options' => [
                'layout' => 'inline',
                'form_group' => false,
                'value_options' => [
                    'sprzedaż' => 'Sprzedaż',
                    'wynajem' => 'Wynajem',
                ],
            ],
        ]);
        $this->add([
            'type' => 'text',
            'name' => 'numer',
            'options' => [
                'label' => 'Numer oferty',
            ],
        ]);
        $this->add([
            'type' => 'text',
            'name' => 'cena',
            'options' => [
                'label' => 'Cena',
            ],
        ]);
        $this->add([
            'type' => 'text',
            'name' => 'powierzchnia',
            'options' => [
                'label' => 'Powierzchnia',
            ],
        ]);
        $this->add([
           'type' => 'Select',
           'name' => 'id_miasto',
           'options' => [
               'label' => 'Miasto',
           ],
           'attributes' => []
        ]);
        $this->add([
            'type' => 'submit',
            'name' => 'dodaj',
            'options' => [
                'label' => 'Dodaj',
            ],
            'attributes' => [
                'class' => 'btn btn-primary',
            ],
        ]);
    }

    public function getInputFilterSpecification()
    {
        return [
            [
                'name' => 'typ_nieruchomosci',
                'required' => true,
                'filters' => [],
                'validators' => [],
            ],
            [
                'name' => 'typ_oferty',
                'required' => true,
                'filters' => [
                ],
                'validators' => [
                ],
            ],
            [
                'name' => 'numer',
                'required' => true,
                'filters' => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                ],
            ],
            [
                'name' => 'cena',
                'required' => true,
                'filters' => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    new Digits(),
                    ['name' => 'GreaterThan', 'options' => ['min' => 0]],
                ],
            ],
            [
                'name' => 'powierzchnia',
                'required' => true,
                'filters' => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    new IsFloat(['locale' => 'en']),
                    ['name' => 'GreaterThan', 'options' => ['min' => 0]],
                ],
            ],
            [
                'name' => 'id_miasto',
                'required' => true,
                'filters' => [],
                'validators' => [],
            ]
        ];
    }


}