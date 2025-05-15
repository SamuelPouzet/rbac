<?php

namespace SamuelPouzet\Rbac\Service;

use Laminas\Router\RouteMatch;
use SamuelPouzet\Auth\Enumerations\AuthStatusEnum;
use SamuelPouzet\Auth\Result\AuthResult;
use SamuelPouzet\Auth\Service\IdentityService;

class AuthService extends \SamuelPouzet\Auth\Service\AuthService
{
    public function __construct(
        protected RbacService $rbacService,
        protected array $config,
        protected IdentityService $identityService
    ) {
        parent::__construct($this->config, $this->identityService);
    }

    protected function grantAccess(string $controller, ?RouteMatch $routeMatch): AuthResult
    {
        $permissive = (bool)$this->config['permissive'] ?? false;
        $action = $routeMatch?->getParam('action');

        $configuration = $this->config['access_filter'][$controller][$action] ?? null;

        if (! $configuration && ! $permissive) {
            // no config found and permission is restrictive, no access
            return new AuthResult(AuthStatusEnum::USER_REQUIRED, null);
        }

        if ($configuration === '*') {
            // no check needed
            return new AuthResult(AuthStatusEnum::GRANTED, null, 'Allowed to everyone');
        }

        if ($this->identityService->hasUser()) {
            return new AuthResult(AuthStatusEnum::GRANTED, null, 'User is connected');
        }
        return new AuthResult(AuthStatusEnum::USER_REQUIRED, null, 'needs connexion');
    }
}
