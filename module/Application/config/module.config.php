<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;


use Application\Controller\Factory\AuthControllerFactory;
use Application\Controller\Factory\BlogControllerFactory;
use Application\Controller\Factory\IndexControllerFactory;
use Application\Service\AuthManager;
use Application\Service\Factory\AuthManagerFactory;
use Application\Service\Factory\PostManagerFactory;
use Application\Service\PostManager;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'home' => [
                'type' => Literal::class,
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
                    ],
                ],
            ],
            'login' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/echome[/:action[/:id]]',
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'admin' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/admin[/:action[/:id]]',
                    'defaults' => [
                        'controller' => Controller\AdminController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'blog' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/blog[/:action[/:id]]',
                    'defaults' => [
                        'controller' => Controller\BlogController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],

    'controllers' => [
        'factories' => [
            Controller\IndexController::class => Controller\Factory\IndexControllerFactory::class,
            Controller\AuthController::class => AuthControllerFactory::class,
            Controller\BlogController::class => BlogControllerFactory::class,
            Controller\AdminController::class => \Application\Controller\Factory\AdminControllerFactory::class,

        ],
    ],

    'service_manager' => [
        'factories' => [
            Service\MailSender::class => InvokableFactory::class,
            \Zend\Authentication\AuthenticationService::class => Service\Factory\AuthenticationServiceFactory::class,
            AuthManager::class => AuthManagerFactory::class,
            PostManager::class => PostManagerFactory::class,
        ],
    ],


    'view_manager' => [
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
    'doctrine' => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/Entity']
            ],
            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                ]
            ]
        ]
    ]
];
