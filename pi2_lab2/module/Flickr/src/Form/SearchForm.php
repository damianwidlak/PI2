<?php

namespace Flickr\Form;

use Flickr\Model\Thumbnails;
use Laminas\Form\Form;
use Laminas\InputFilter\InputFilterProviderInterface;

class SearchForm extends Form implements InputFilterProviderInterface
{
  public function __construct(Thumbnails $thumbnails)
  {
    parent::__construct('thumbnails');

    $this->setAttributes(['method' => 'post', 'class' => 'form']);
    $this->add([
      'name' => 'phrase',
      'type' => 'Text',
      'options' => [
        'label' => 'Szukana fraza',
      ],
      'attributes' => ['class' => 'form-control'],
    ]);

    $this->add([
      'name' => 'search',
      'type' => 'Submit',
      'attributes' => [
        'value' => 'Search',
        'class' => 'btn btn-primary',
      ],
    ]);
  }

  public function getInputFilterSpecification()
  {
    return [
      [
        'name' => 'phrase',
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
