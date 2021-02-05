<?php

namespace ApiKlient;

use ApiKlient\Controller\IndexController;
use ApiKlient\Controller\IndexControllerFactory;
use ApiKlient\Form\NieruchomoscForm;
use ApiKlient\Model\Nieruchomosci;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'api-klient' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/api-klient',
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
                            'route' => '[/:action[/:id]]',
                            'defaults' => [
                                'controller' => IndexController::class,
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'service_manager' => [
        'factories' => [
            NieruchomoscForm::class => InvokableFactory::class,
        ]
    ],
    'controllers' => [
        'factories' => [
            IndexController::class => IndexControllerFactory::class,
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];