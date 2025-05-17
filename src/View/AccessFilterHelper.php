<?php

namespace SamuelPouzet\Rbac\View;

use Laminas\View\Helper\AbstractHelper;
use SamuelPouzet\Rbac\Service\RbacService;

class AccessFilterHelper extends AbstractHelper
{
    public function __construct(protected readonly RbacService $rbacService)
    {
    }

    public function __invoke(string $permission, array $params = []): bool
    {
        return $this->rbacService->isGranted(null, $permission);
    }
}
