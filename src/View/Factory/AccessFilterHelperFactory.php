<?php

namespace SamuelPouzet\Rbac\View\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use SamuelPouzet\Rbac\Service\RbacService;
use SamuelPouzet\Rbac\View\AccessFilterHelper;

class AccessFilterHelperFactory implements FactoryInterface
{

    /**
     * @inheritDoc
     */
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): AccessFilterHelper
    {
        $rbacService = $container->get(RbacService::class);
        return new AccessFilterHelper($rbacService);
    }
}
