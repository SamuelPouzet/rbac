<?php
namespace Samuelpouzet\Module;

use Doctrine\ORM\Mapping\Driver\AttributeDriver;
use Laminas\Cache\Storage\Adapter\Filesystem;
use Laminas\Cache\Storage\Plugin\Serializer;
use SamuelPouzet\Rbac\Entity\Permission;
use SamuelPouzet\Rbac\Entity\Role;
use SamuelPouzet\Rbac\Entity\User;
use SamuelPouzet\Auth\Interface\UserInterface;
use SamuelPouzet\Auth\Listener\AuthListener;
use SamuelPouzet\Rbac\Interface\Entities\PermissionInterface;
use SamuelPouzet\Rbac\Interface\Entities\RoleInterface;
use SamuelPouzet\Rbac\Listener\Factory\AuthListenerFactory;
use SamuelPouzet\Rbac\Service\AuthService;
use SamuelPouzet\Rbac\Service\Factory\AuthServiceFactory;
use SamuelPouzet\Rbac\Service\Factory\RbacServiceFactory;
use SamuelPouzet\Rbac\Service\RbacService;

return [
    'samuelpouzet' => [

    ],
    'service_manager' => [
        'factories' => [
            //listeners
            AuthListener::class => AuthListenerFactory::class,
            //services
            AuthService::class => AuthServiceFactory::class,
            RbacService::class => RbacServiceFactory::class,
        ]
    ],
    'doctrine' => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => AttributeDriver::class,
                'cache' => 'array',
                'paths' => [dirname(__DIR__, 1) . '/Entity']
            ],
            'orm_default' => [
                'drivers' => [
                    'SamuelPouzet\Rbac\Entity' => __NAMESPACE__ . '_driver'
                ],
            ],
        ],
        'entity_resolver' => [
            'orm_default' => [
                'resolvers' => [
                    UserInterface::class => User::class,
                    RoleInterface::class => Role::class,
                    PermissionInterface::class => Permission::class,
                ]
            ],
        ],
    ],
    'caches' => [
        'default-cache' => [
            'adapter' => Filesystem::class,
            'plugins' => [
                [
                    'name' => Serializer::class,
                    'options' => [
                    ],
                ],
            ],
            'options' => [
                'cache_dir' => dirname(__DIR__, 1) . '/data/cache',
            ],
        ],
    ],
];
