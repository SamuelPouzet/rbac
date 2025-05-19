<?php

namespace SamuelPouzet\Rbac\Manager\Factory;

use Doctrine\ORM\EntityManagerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use SamuelPouzet\Rbac\Manager\RoleManager;

class RoleManagerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): RoleManager
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        return new RoleManager($entityManager);
    }
}
