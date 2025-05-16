<?php

namespace SamuelPouzet\Rbac\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use SamuelPouzet\Auth\Entity\AbstractEntity;
use SamuelPouzet\Rbac\Interface\Entities\PermissionInterface;
use SamuelPouzet\Rbac\Interface\Entities\RoleInterface;

#[ORM\Entity]
#[ORM\Table(name: 'permission')]
class Permission extends AbstractEntity implements PermissionInterface
{
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: 'integer', options: ['unsigned' => true, 'notnull' => true])]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    protected int $id;

    #[ORM\Column(name: 'name', type: 'string', length: 200, nullable: false)]
    protected string $name;

    #[ORM\Column(name: 'code', type: 'string', length: 200, nullable: false)]
    protected string $code;

    #[ORM\Column(name: 'description', type: 'string', length: 200, nullable: false)]
    protected string $description;

    #[ORM\Column(name: 'date_created', type: 'datetime_immutable', options: ['nullable' => false])]
    protected \DateTimeImmutable $dateCreated;

    #[ORM\ManyToMany(targetEntity: RoleInterface::class, mappedBy: 'roles')]
    private Collection $roles;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->roles = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getDateCreated(): \DateTimeImmutable
    {
        return $this->dateCreated;
    }

    public function setDateCreated(\DateTimeImmutable $dateCreated): static
    {
        $this->dateCreated = $dateCreated;
        return $this;
    }

    public function getRoles(): Collection
    {
        return $this->roles;
    }

    public function setRoles(Collection $roles): static
    {
        $this->roles = $roles;
        return $this;
    }

    public function addRole(Role $role): static
    {
        $this->roles->add($role);
        return $this;
    }

    public function removeRole(Role $role): static
    {
        $this->roles->removeElement($role);
        return $this;
    }
}
