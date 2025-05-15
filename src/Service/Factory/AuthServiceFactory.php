<?php

namespace SamuelPouzet\Rbac\Service\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use SamuelPouzet\Auth\Service\IdentityService;
use SamuelPouzet\Rbac\Service\AuthService;

class AuthServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $config = $container->get('config')['authentication'] ?? [];
        $identityService = $container->get(IdentityService::class);
        return new AuthService($config, $identityService);
    }
}
