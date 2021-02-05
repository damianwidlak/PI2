<?php

namespace Dropbox;

use Dropbox\Controller\IndexController;
use Dropbox\Service\Dropbox;
use Laminas\Mvc\Controller\LazyControllerAbstractFactory;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\AbstractFactory\ReflectionBasedAbstractFactory;

return [
	'router' => [
        'routes' => [
            'dropbox' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/dropbox',
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
            Dropbox::class => ReflectionBasedAbstractFactory::class,
        ]
    ],
	'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    'dropbox' => [
        'key' => '	qxkq898qcm0dfvv',
        'secret' => '3yz8vyi02n2136u'
    ]
];