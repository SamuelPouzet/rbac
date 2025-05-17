<?php

namespace SamuelPouzet\Rbac\Plugin;

use Laminas\Mvc\Controller\Plugin\AbstractPlugin;
use Laminas\Mvc\Controller\Plugin\PluginInterface;
use SamuelPouzet\Rbac\Service\RbacService;

class AccessFilterPlugin extends AbstractPlugin implements PluginInterface
{
    public function __construct(protected readonly RbacService $rbacService)
    {
    }

    public function __invoke(string $permission, array $params = []): bool
    {
        return $this->rbacService->isGranted(null, $permission);
    }
}
