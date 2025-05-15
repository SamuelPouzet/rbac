<?php

namespace SamuelPouzet\Rbac\Service;

use Doctrine\ORM\EntityManagerInterface;
use Laminas\Cache\Storage\StorageInterface;
use Laminas\Permissions\Rbac\Rbac;
use SamuelPouzet\Auth\Enumerations\UserStatusEnum;
use SamuelPouzet\Auth\Interface\UserInterface;
use SamuelPouzet\Auth\Service\IdentityService;
use SamuelPouzet\Rbac\Interface\Entities\RoleInterface;

class RbacService
{
    protected Rbac $rbac;

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
                ->findBy(['status' => UserStatusEnum::ACTIVE], ['id' => 'ASC']);

            foreach ($roles as $role) {
                $roleName = $role->getName();

                $parentRoleNames = [];
                foreach ($role->getParentRoles() as $parentRole) {
                    $parentRoleNames[] = $parentRole->getName();
                }

                $this->rbac->addRole($roleName, $parentRoleNames);

                foreach ($role->getPermissions() as $permission) {
                    $this->rbac->getRole($roleName)->addPermission($permission->getName());
                }
            }
            $this->cache->setItem('rbac_container', $this->rbac);
        }
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
//            if ($this->rbac->isGranted($role->getName(), $permission)) {

//                if ($params==null)
//                    return true;

//                foreach ($this->assertionManagers as $assertionManager) {
//                    if ($assertionManager->assert($this->rbac, $permission, $params))
//                        return true;
//                }
//            }

            $parentRoles = $role->getParentRoles();
            foreach ($parentRoles as $parentRole) {
                if ($this->rbac->isGranted($parentRole->getName(), $permission)) {
                    return true;
                }
            }
        }
        return false;
    }
}
