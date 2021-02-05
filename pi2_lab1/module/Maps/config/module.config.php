<?php

namespace Maps;

use Maps\Controller\AddressController;
use Maps\Controller\AddressControllerFactory;
use Maps\Controller\IndexController;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\AbstractFactory\ReflectionBasedAbstractFactory;
use Maps\Model\Address;
use Maps\Form\AddressForm;
use Laminas\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'maps' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/maps[/:action]',
                    'defaults' => [
                        'controller' => AddressController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],

    'controllers' => [
        'factories' => [
            // IndexController::class => InvokableFactory::class,
            AddressController::class => AddressControllerFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            Address::class => InvokableFactory::class,
            AddressForm::class => ReflectionBasedAbstractFactory::class,
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
