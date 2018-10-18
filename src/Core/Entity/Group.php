<?php

namespace Siqu\CMS\Core\Entity;

use Doctrine\ORM\Mapping as ORM;
use Siqu\CMS\Core\Entity\Traits\IdentifiableTrait;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Group
 * @package Siqu\CMS\Core\Entity
 * @ORM\Entity()
 * @ORM\Table(name="cms_group")
 */
class Group
{
    use IdentifiableTrait;

    /**
     * @var string
     * @ORM\Column(name="name", type="string")
     * @Groups({"api"})
     * @Assert\NotBlank(groups={"new", "update"})
     */
    protected $name;

    /**
     * @var array
     * @ORM\Column(name="roles", type="array")
     * @Groups({"api"})
     */
    protected $roles;

    /**
     * Group constructor.
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
        $this->roles = [];
    }

    /**
     * Set the name.
     *
     * @param string $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * Retrieve the name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Add a new role.
     *
     * @param string $role
     */
    public function addRole(string $role): void
    {
        if (!$this->hasRole($role)) {
            $this->roles[] = strtoupper($role);
        }
    }

    /**
     * Check if the group has a specific role.
     *
     * @param string $role
     * @return bool
     */
    public function hasRole(string $role): bool
    {
        return in_array(strtoupper($role), $this->roles, true);
    }

    /**
     * Retrieve the roles.
     *
     * @return array
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * Remove a specific role.
     *
     * @param string $role
     */
    public function removeRole(string $role): void
    {
        if ($this->hasRole($role)) {
            $key = array_search(strtoupper($role), $this->roles, true);
            unset($this->roles[$key]);
            $this->roles = array_values($this->roles);
        }
    }

    /**
     * Set roles.
     *
     * @param array $roles
     */
    public function setRoles($roles): void
    {
        $this->roles = [];

        foreach($roles as $role) {
            $this->addRole($role);
        }
    }
}