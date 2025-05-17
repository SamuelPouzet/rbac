<?php

namespace SamuelPouzet\Rbac\Service;

use Laminas\Router\RouteMatch;
use SamuelPouzet\Auth\Enumerations\AuthStatusEnum;
use SamuelPouzet\Auth\Result\AuthResult;
use SamuelPouzet\Auth\Service\IdentityService;

class AuthService extends \SamuelPouzet\Auth\Service\AuthService
{
    public function __construct(
        protected readonly RbacService $rbacService,
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

        if (! $configuration) {
            if ($permissive) {
                return new AuthResult(AuthStatusEnum::GRANTED, null, 'Access by permissive configuration');
            }
            // no config found and permission is restrictive, no access
            return new AuthResult(AuthStatusEnum::USER_REQUIRED, null);
        }

        if ($configuration === '*') {
            // no check needed
            return new AuthResult(AuthStatusEnum::GRANTED, null, 'Allowed to everyone');
        }

        if (! $this->identityService->hasUser()) {
            return new AuthResult(AuthStatusEnum::USER_REQUIRED, null, 'needs connexion');
        }

        $account = $this->identityService->getUser();

        if ($configuration === '@') {
            return new AuthResult(AuthStatusEnum::GRANTED, null, 'Allowed to all connections');
        }

        $users = $configuration['users'] ?? [];
        if (in_array($account->getLogin(), $users)) {
            //allowed for this user
            return new AuthResult(
                AuthStatusEnum::GRANTED,
                $this->identityService->getUser(),
                'Allowed to this user specially'
            );
        }

        $permissions = $configuration['permissions'] ?? [];
        foreach ($permissions as $permission) {
            if ($this->rbacService->isGranted(null, $permission)) {
                return new AuthResult(AuthStatusEnum::GRANTED, null, $permission);
            }
        }

        return new AuthResult(AuthStatusEnum::DENIED, null, 'Access denied to this user');
    }
}
