<?php
namespace Samuelpouzet\Module;

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

return [
    'samuelpouzet' => [

    ],
    'service_manager' => [
        'factories' => [
            //listeners
            AuthListener::class => AuthListenerFactory::class,
            //services
            AuthService::class => AuthServiceFactory::class,
        ]
    ],
    'doctrine' => [
        'entity_resolver' => [
            'orm_default' => [
                'resolvers' => [
                    UserInterface::class => User::class,
                    RoleInterface::class => Role::class,
                    PermissionInterface::class => Permission::class,
                ]
            ],
        ],
    ]
];
