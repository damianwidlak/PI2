<?php

namespace ApiSerwer;

use ApiSerwer\Controller\IndexController;
use ApiSerwer\Model\Nieruchomosci;
use Laminas\Mvc\Controller\LazyControllerAbstractFactory;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\AbstractFactory\ReflectionBasedAbstractFactory;

return [
    'router' => [
        'routes' => [
            'api-serwer' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/api-serwer',
                    'defaults' => [
                        'controller' => IndexController::class,
                        'action' => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'akcje' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '[/:action]',
                            'defaults' => [
                                'controller' => IndexController::class,
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            IndexController::class => LazyControllerAbstractFactory::class,
        ]
    ],
    'service_manager' => [
        'factories' => [
            Nieruchomosci::class => ReflectionBasedAbstractFactory::class,
        ]
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    'klucz_api' => 'hgfJGFG&%^&*42342c',
];