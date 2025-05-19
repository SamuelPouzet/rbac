<?php

namespace SamuelPouzet\Rbac\Manager;

use Doctrine\ORM\EntityManagerInterface;
use SamuelPouzet\Rbac\Entity\Role;
use SamuelPouzet\Rbac\Interface\Entities\RoleInterface;

class RoleManager
{
    public function __construct(protected readonly EntityManagerInterface $entityManager)
    {
    }

    public function createRole(array $data): RoleInterface
    {
        $role = new Role();

        $role->setName($data['name']);
        $role->setDescription($data['description']);
        $role->setDateCreated(new \DateTimeImmutable());

        $this->entityManager->persist($role);
        $this->entityManager->flush();
        return $role;
    }

    public function updateRole(array $data, RoleInterface $role): RoleInterface
    {

        if ($data['name'] !== $role->getName()) {
            $role->setName($data['name']);
        }
        if ($data['description'] !== $role->getDescription()) {
            $role->setDescription($data['description']);
        }

        $this->entityManager->flush();
        return $role;
    }
}
