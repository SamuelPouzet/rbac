<?php

namespace Samuelpouzet\Module;

use Doctrine\ORM\Mapping\Driver\AttributeDriver;
use Laminas\Cache\Storage\Adapter\Filesystem;
use Laminas\Cache\Storage\Plugin\Serializer;
use SamuelPouzet\Rbac\Form\UpdateRoleForm;
use SamuelPouzet\Rbac\Interface\Entities\UpdateRoleInterface;
use SamuelPouzet\Rbac\Manager\Factory\RoleManagerFactory;
use SamuelPouzet\Rbac\Manager\RoleManager;
use SamuelPouzet\Rbac\Entity\Permission;
use SamuelPouzet\Rbac\Entity\Role;
use SamuelPouzet\Rbac\Entity\User;
use SamuelPouzet\Auth\Interface\UserInterface;
use SamuelPouzet\Auth\Listener\AuthListener;
use SamuelPouzet\Rbac\Form\NewRoleForm;
use SamuelPouzet\Rbac\Interface\Entities\NewRoleInterface;
use SamuelPouzet\Rbac\Interface\Entities\PermissionInterface;
use SamuelPouzet\Rbac\Interface\Entities\RoleInterface;
use SamuelPouzet\Rbac\Listener\Factory\AuthListenerFactory;
use SamuelPouzet\Rbac\Plugin\AccessFilterPlugin;
use SamuelPouzet\Rbac\Plugin\Factory\AccessFilterPluginFactory;
use SamuelPouzet\Rbac\Service\AuthService;
use SamuelPouzet\Rbac\Service\Factory\AuthServiceFactory;
use SamuelPouzet\Rbac\Service\Factory\RbacServiceFactory;
use SamuelPouzet\Rbac\Service\RbacService;
use SamuelPouzet\Rbac\View\AccessFilterHelper;
use SamuelPouzet\Rbac\View\Factory\AccessFilterHelperFactory;

return [
    'samuelpouzet' => [
        'form_resolver' => [
            NewRoleInterface::class => NewRoleForm::class,
            UpdateRoleInterface::class => UpdateRoleForm::class,
        ],
        'auth' => [
            'default_user' => [
                'login' => 'admin',
                'password' => 'Secur1ty!',
                'email' => 'admin@exemple.com',
                'role' => 'role.admin',
            ],
            'default_roles' => [
                [
                    'name' => 'Admin',
                    'code' => 'role.admin',
                    'description' => 'Administrator',
                ],
                [
                    'name' => 'Guest',
                    'code' => 'role.guest',
                    'description' => 'Guest',
                ],
            ],
            'default_permissions' => [
                [
                    'name' => 'Manage users',
                    'code' => 'permission.manage.user',
                    'description' => 'Permission to manage users',
                ],
                [
                    'name' => 'View',
                    'code' => 'permission.view',
                    'description' => 'Permission to view page',
                ],
            ],
        ],
    ],
    'controller_plugins' => [
        'factories' => [
            AccessFilterPlugin::class => AccessFilterPluginFactory::class,
        ],
        'aliases' => [
            'isGranted' => AccessFilterPlugin::class,
        ]
    ],
    'service_manager' => [
        'factories' => [
            //listeners
            AuthListener::class => AuthListenerFactory::class,
            //managers
            RoleManager::class => RoleManagerFactory::class,
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
    'view_helpers' => [
        'factories' => [
            AccessFilterHelper::class => AccessFilterHelperFactory::class,
        ],
        'aliases' => [
            'isGranted' => AccessFilterHelper::class,
        ]
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
