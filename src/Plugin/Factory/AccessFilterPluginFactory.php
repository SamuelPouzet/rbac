<?php

namespace SamuelPouzet\Rbac\Plugin\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use SamuelPouzet\Rbac\Plugin\AccessFilterPlugin;
use SamuelPouzet\Rbac\Service\RbacService;

class AccessFilterPluginFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): AccessFilterPlugin
    {
        $rbacService = $container->get(RbacService::class);
        return new AccessFilterPlugin($rbacService);
    }
}
