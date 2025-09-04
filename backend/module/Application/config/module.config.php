<?php

declare(strict_types=1);

namespace Application;

use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
    'mock-nfe-emitir' => [
        'type' => \Laminas\Router\Http\Literal::class,
        'options' => [
            'route' => '/mock/nfe/emitir',
            'defaults' => [
                'controller' => \Application\Controller\MockNfeController::class,
                'action' => 'emitir',
                ],
            ],
        ],

        'mock-nfe-consultar' => [
            'type' => \Laminas\Router\Http\Literal::class,
            'options' => [
                'route' => '/mock/nfe/consultar',
                'defaults' => [
                    'controller' => \Application\Controller\MockNfeController::class,
                    'action' => 'consultar',
                ],
            ],
        ],

        'client' => [
            'type'    => 'Segment',
            'options' => [
                'route'    => '/client[/:action[/:id]]',
                'defaults' => [
                    'controller' => \Application\Controller\ClientController::class,
                    'action'     => 'list', 
                ],
            ],
        ],

'clients-list' => [
    'type' => \Laminas\Router\Http\Literal::class,
    'options' => [
        'route' => '/clients',
        'defaults' => [
            'controller' => \Application\Controller\ClientController::class,
            'action' => 'list',
        ],
    ],
],



  'clients-get' => [
    'type' => \Laminas\Router\Http\Segment::class,
    'options' => [
        'route' => '/clients/:id',
        'defaults' => [
            'controller' => \Application\Controller\ClientController::class,
            'action' => 'get',
        ],
        'constraints' => [
            'id' => '[0-9]+',
        ],
    ],
],

  'clients-update' => [
    'type' => \Laminas\Router\Http\Segment::class,
    'options' => [
        'route' => '/clients/update/:id',
        'defaults' => [
            'controller' => \Application\Controller\ClientController::class,
            'action' => 'update',
        ],
        'constraints' => [
            'id' => '[0-9]+',
        ],
    ],
],

  'clients-delete' => [
    'type' => \Laminas\Router\Http\Segment::class,
    'options' => [
        'route' => '/clients/delete/:id',
        'defaults' => [
            'controller' => \Application\Controller\ClientController::class,
            'action' => 'delete',
        ],
        'constraints' => [
            'id' => '[0-9]+',
        ],
    ],
],

'clients-create' => [
    'type' => \Laminas\Router\Http\Literal::class,
    'options' => [
        'route' => '/clients/create',
        'defaults' => [
            'controller' => \Application\Controller\ClientController::class,
            'action' => 'create',
        ],
    ],
],

'orders-list' => [
    'type' => \Laminas\Router\Http\Literal::class,
    'options' => [
        'route' => '/orders',
        'defaults' => [
            'controller' => \Application\Controller\OrderController::class,
            'action' => 'list',
        ],
    ],
],

'orders-create' => [
    'type' => \Laminas\Router\Http\Literal::class,
    'options' => [
        'route' => '/orders/create',
        'defaults' => [
            'controller' => \Application\Controller\OrderController::class,
            'action' => 'create',
        ],
    ],
],

 'orders-cancel' => [
    'type' => \Laminas\Router\Http\Literal::class,
    'options' => [
        'route' => '/orders/cancel',
        'defaults' => [
            'controller' => \Application\Controller\OrderController::class,
            'action' => 'cancel',
        ],
    ],
],

  'orders-get' => [
    'type' => \Laminas\Router\Http\Segment::class,
    'options' => [
        'route' => '/orders/get/:id',
        'defaults' => [
            'controller' => \Application\Controller\OrderController::class,
            'action' => 'get'
        ],
    ],
],

  'order-update' => [
    'type' => \Laminas\Router\Http\Segment::class,
    'options' => [
        'route' => '/orders/update/:id',
        'defaults' => [
            'controller' => \Application\Controller\OrderController::class,
            'action' => 'update',
        ],
    ],
],

  'order-delete' => [
    'type' => \Laminas\Router\Http\Segment::class,
    'options' => [
        'route' => '/orders/delete/:id',
        'defaults' => [
            'controller' => \Application\Controller\OrderController::class,
            'action' => 'delete',
        ],
    ],
],

 'product-create' => [
                'type' => \Laminas\Router\Http\Literal::class,
                'options' => [
                    'route' => '/products/',
                    'defaults' => [
                        'controller' => \Application\Controller\ProductController::class,
                        'action' => 'create',
                    ],
                ],
            ],

            'product-list' => [
                'type' => \Laminas\Router\Http\Literal::class,
                'options' => [
                    'route' => '/products',
                    'defaults' => [
                        'controller' => \Application\Controller\ProductController::class,
                        'action' => 'list',
                    ],
                ],
            ],

            'product-get' => [
               'type' => \Laminas\Router\Http\Segment::class,
              'options' => [
                 'route' => '/products/:id',
                 'defaults' => [
                    'controller' => \Application\Controller\ProductController::class,
                    'action' => 'get',
        ],
    ],
],

'product-update' => [
    'type' => \Laminas\Router\Http\Segment::class,
    'options' => [
        'route' => '/products/update/:id',
        'defaults' => [
            'controller' => \Application\Controller\ProductController::class,
            'action' => 'update',
        ],
    ],
],

'product-delete' => [
    'type' => \Laminas\Router\Http\Segment::class,
    'options' => [
        'route' => '/products/delete/:id',
        'defaults' => [
            'controller' => \Application\Controller\ProductController::class,
            'action' => 'delete',
        ],
    ],
],


            'user-login' => [
            'type' => \Laminas\Router\Http\Literal::class,
            'options' => [
            'route' => '/login',
            'defaults' => [
            'controller' => \Application\Controller\UserController::class,
            'action' => 'login',
        ],
    ],
],

            'user-register' => [
            'type' => \Laminas\Router\Http\Literal::class,
            'options' => [
            'route' => '/register',
            'defaults' => [
            'controller' => \Application\Controller\UserController::class,
            'action' => 'register',
        ],
    ],
],
            'health' => [
            'type'    => \Laminas\Router\Http\Literal::class,
            'options' => [
            'route'    => '/health',
            'defaults' => [
            'controller' => \Application\Controller\HealthController::class,
            'action'     => 'index',
        ],
    ],
],

            'home' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'application' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/application[/:action]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
     ],
 ],
    'controllers' => [
        'factories' => [
            \Application\Controller\ClientController::class => \Laminas\ServiceManager\Factory\InvokableFactory::class,
            \Application\Controller\OrderController::class => \Laminas\ServiceManager\Factory\InvokableFactory::class,
            \Application\Controller\UserController::class => \Laminas\ServiceManager\Factory\InvokableFactory::class,
            \Application\Controller\ProductController::class => \Laminas\ServiceManager\Factory\InvokableFactory::class,
            \Application\Controller\HealthController::class => \Laminas\ServiceManager\Factory\InvokableFactory::class,
            \Application\Controller\MockNfeController::class => InvokableFactory::class,
            Controller\IndexController::class => InvokableFactory::class,
        ],
    ],
    'view_manager' => [
        'strategies' => ['ViewJsonStrategy'],
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
