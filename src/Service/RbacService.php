<?php

namespace SamuelPouzet\Rbac\Service;

use Doctrine\ORM\EntityManagerInterface;
use Laminas\Cache\Storage\StorageInterface;
use Laminas\Permissions\Rbac\Rbac;
use Laminas\Permissions\Rbac\Role;
use SamuelPouzet\Auth\Interface\UserInterface;
use SamuelPouzet\Auth\Service\IdentityService;
use SamuelPouzet\Rbac\Interface\Entities\RoleInterface;

class RbacService
{
    protected ?Rbac $rbac = null;

    public function __construct(
        protected StorageInterface $cache,
        protected EntityManagerInterface $entityManager,
        protected IdentityService $identityService,
    ) {
    }

    public function init(bool $force = false): void
    {
        if (! ! $this->rbac && ! $force) {
            return;
        }

        if ($force) {
            $this->cache->removeItem('rbac_container');
        }

        $result = false;
        $this->rbac = $this->cache->getItem('rbac_container', $result);
        if (! $result) {
            $this->rbac = new Rbac();
            $roles = $this->entityManager->getRepository(RoleInterface::class)
                ->findBy([], ['id' => 'ASC']);

            foreach ($roles as $role) {
                    $this->createRole($role);
            }
        }
        $this->cache->setItem('rbac_container', $this->rbac);
    }

    public function isGranted(?UserInterface $user, string $permission, ?array $params = null): bool
    {
        if (! $this->rbac) {
            $this->init();
        }

        if (! $user) {
            if (! $this->identityService->hasUser()) {
                return false;
            }
            $user = $this->identityService->getUser();
        }

        $roles = $user->getRoles();
        foreach ($roles as $role) {
            if ($this->rbac->isGranted($role->getName(), $permission)) {
                if ($params === null) {
                    return true;
                }

//                foreach ($this->assertionManagers as $assertionManager) {
//                    if ($assertionManager->assert($this->rbac, $permission, $params)){
//                        return true;
//                    }
//                }
            }
        }
        return false;
    }


    protected function createRole(RoleInterface $role): void
    {
        if ($this->rbac->hasRole($role->getName())) {
            return;
        }
        $rbacRole = new Role($role->getName());
        foreach ($role->getParentRoles() as $parentRole) {
            if (! $this->rbac->hasRole($parentRole->getName())) {
                $this->createRole($parentRole);
            }
            $rbacRole->addParent($this->rbac->getRole($parentRole->getName()));
        }
        foreach ($role->getPermissions() as $permission) {
            $rbacRole->addPermission($permission->getName());
        }
        $this->rbac->addRole($rbacRole);
    }
}
