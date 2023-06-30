<?php
namespace Leave;

use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'leave' => [
                'type' => Literal::class,
                'priority' => 1,
                'options' => [
                    'route' => '/leave',
                    'defaults' => [
                        'controller' => Controller\LeaveController::class,
                    ],
                ],
                'may_terminate' => TRUE,
                'child_routes' => [
                    'config' => [
                        'type' => Segment::class,
                        'priority' => 100,
                        'options' => [
                            'route' => '/config[/:action]',
                            'defaults' => [
                                'action' => 'index',
                                'controller' => Controller\LeaveConfigController::class,
                            ],
                        ],
                    ],
                ],
                'default' => [
                    'type' => Segment::class,
                    'priority' => -100,
                    'options' => [
                        'route' => '/[:action[/:uuid]]',
                        'defaults' => [
                            'action' => 'index',
                            'controller' => Controller\LeaveController::class,
                        ],
                    ],
                ],
            ],
        ],
    ],
    'acl' => [
        'admin' => [
            'leave/config' => [],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\LeaveController::class => Controller\Factory\LeaveControllerFactory::class,
            Controller\LeaveConfigController::class => Controller\Factory\LeaveConfigControllerFactory::class,
        ],
    ],
    'navigation' => [
        'default' => [
            'settings' => [
                'label' => 'Settings',
                'pages' => [
                    [
                        'label' => 'Leave Settings',
                        'route' => 'leave/config',
                        'action' => 'index',
                        'resource' => 'leave/config',
                        'privilege' => 'index',
                    ],
                ],
            ],
        ],
    ],
    'service_manager' => [
        'aliases' => [
            'leave-model-adapter' => 'timecard-model-adapter',
        ],
    ],
    'view_manager' => [
        'template_map' => [
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];