<?php

namespace SamuelPouzet\Rbac\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'role')]
class Role
{
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: 'integer', options: ['unsigned' => true, 'notnull' => true])]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    protected int $id;

    #[ORM\Column(name: 'name', type: 'string', length: 200, nullable: false)]
    protected string $name;

    #[ORM\Column(name: 'description', type: 'string', length: 200, nullable: false)]
    protected string $description;

    #[ORM\Column(name: 'date_created', type: \DateTimeImmutable::class, options: ['nullable' => false])]
    protected \DateTimeImmutable $dateCreated;

    #[ORM\ManyToMany(targetEntity: self::class, inversedBy: 'role')]
    #[ORM\JoinTable(name: 'role_hierarchy', joinColumns: [])]
    #[ORM\JoinColumn(name: "child_role_id", referencedColumnName: "id")]
    #[ORM\InverseJoinColumn(name: "parent_role_id", referencedColumnName: "id")]
    private Collection $parentRoles;

    #[ORM\ManyToMany(targetEntity: self::class, inversedBy: 'role')]
    #[ORM\JoinTable(name: 'role_hierarchy', joinColumns: [])]
    #[ORM\JoinColumn(name: "parent_role_id", referencedColumnName: "id")]
    #[ORM\InverseJoinColumn(name: "child_role_id", referencedColumnName: "id")]
    protected Collection $childRoles;


    #[ORM\ManyToMany(targetEntity: Permission::class, inversedBy: 'roles')]
    #[ORM\JoinTable(name: 'role_permission', joinColumns: [])]
    #[ORM\JoinColumn(name: "role_id", referencedColumnName: "id")]
    #[ORM\InverseJoinColumn(name: "permission_id", referencedColumnName: "id")]
    private Collection $permissions;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->parentRoles = new ArrayCollection();
        $this->childRoles = new ArrayCollection();
        $this->permissions = new ArrayCollection();
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

    public function getParentRoles(): Collection
    {
        return $this->parentRoles;
    }

    public function setParentRoles(Collection $parentRoles): static
    {
        $this->parentRoles = $parentRoles;
        return $this;
    }

    public function getChildRoles(): Collection
    {
        return $this->childRoles;
    }

    public function setChildRoles(Collection $childRoles): static
    {
        $this->childRoles = $childRoles;
        return $this;
    }

    public function getPermissions(): Collection
    {
        return $this->permissions;
    }

    public function setPermissions(Collection $permissions): static
    {
        $this->permissions = $permissions;
        return $this;
    }
}
