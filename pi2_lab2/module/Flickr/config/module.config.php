<?php

namespace Flickr;

use Flickr\Controller\FlickrController;
use Flickr\Controller\FlickrControllerFactory;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\AbstractFactory\ReflectionBasedAbstractFactory;
use Flickr\Model\Thumbnails;
use Flickr\Form\SearchForm;
use Laminas\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'flickr' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/flickr',
                    'defaults' => [
                        'controller' => FlickrController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'default' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '[/:action[/:id[/:page]]]',
                            'defaults' => [
                                'controller' => FlickrController::class,
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'controllers' => [
        'factories' => [
            FlickrController::class => FlickrControllerFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            Thumbnails::class => InvokableFactory::class,
            SearchForm::class => ReflectionBasedAbstractFactory::class,
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
