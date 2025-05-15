<?php

namespace SamuelPouzet\Rbac\Service\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use SamuelPouzet\Auth\Service\IdentityService;
use SamuelPouzet\Rbac\Service\RbacService;

class RbacServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): RbacService
    {
        $cache = $container->get('default-cache');
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $idenntityService = $container->get(IdentityService::class);
        return new RbacService($cache, $entityManager, $idenntityService);
    }
}
