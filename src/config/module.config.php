<?php
namespace Samuelpouzet\Module;

use Laminas\ServiceManager\Factory\InvokableFactory;
use SamuelPouzet\Auth\Listener\AuthListener;
use SamuelPouzet\Rbac\Listener\Factory\AuthListenerFactory;

return [
    'samuelpouzet' => [

    ],
    'service_manager' => [
        'factories' => [
            //listeners
            AuthListener::class => AuthListenerFactory::class,
        ]
    ],
];
