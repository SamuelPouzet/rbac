<?php

namespace SamuelPouzet\Rbac\Listener\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use SamuelPouzet\Rbac\Listener\AuthListener;

class AuthListenerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        return new AuthListener();
    }
}
