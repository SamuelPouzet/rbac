<?php

namespace SamuelPouzet\Rbac\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use SamuelPouzet\Auth\Entity\AbstractEntity;
use SamuelPouzet\Auth\Enumerations\UserStatusEnum;
use SamuelPouzet\Auth\Interface\UserInterface;

#[ORM\Entity]
#[ORM\Table(name: 'user')]
class User extends AbstractEntity implements UserInterface
{
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: 'integer', options: ['unsigned' => true, 'notnull' => true])]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    protected int $id;

    #[ORM\Column(name: 'login', type: 'string', length: 255)]
    protected string $login;

    #[ORM\Column(name: 'email', type: 'string', length: 255)]
    protected string $email;

    #[ORM\Column(name: 'password', type: 'string', length: 255)]
    protected string $password;

    #[ORM\Column(name: 'status', length: 200, nullable: false, enumType: UserStatusEnum::class)]
    protected UserStatusEnum $status = UserStatusEnum::NOT_CONFIRMED;

    #[ORM\Column(name: 'date_created', type: 'datetime', nullable: false)]
    protected \DateTime $date_created;

    #[ORM\Column(name: 'token', type: 'string', length: 200, nullable: false)]
    protected ?string $token = null;

    #[ORM\ManyToMany(targetEntity: Role::class, inversedBy: 'users')]
    #[ORM\JoinTable(name: 'user_role')]
    #[ORM\JoinColumn(name: "user_id", referencedColumnName: "id")]
    #[ORM\InverseJoinColumn(name: "role_id", referencedColumnName: "id")]
    #[ORM\ManyToMany(targetEntity: Role::class, inversedBy: 'users')]
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

    public function getLogin(): string
    {
        return $this->login;
    }

    public function setLogin(string $login): static
    {
        $this->login = $login;
        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): static
    {
        $this->token = $token;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getStatus(): UserStatusEnum
    {
        return $this->status;
    }

    public function setStatus(UserStatusEnum $status): static
    {
        $this->status = $status;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function getDateCreated(): \DateTime
    {
        return $this->date_created;
    }

    public function setDateCreated(\DateTime $date_created): static
    {
        $this->date_created = $date_created;
        return $this;
    }

    public function getRoles(): Collection
    {
        return $this->roles;
    }

    public function getRolesAsString(): string
    {
        $roleList = '';

        $count = count($this->roles);
        $i = 0;
        foreach ($this->roles as $role) {
            $roleList .= $role->getName();
            if ($i < $count - 1) {
                $roleList .= ', ';
            }
            $i++;
        }

        return $roleList;
    }

    public function addRole($role): static
    {
        $this->roles->add($role);
        return $this;
    }
}
