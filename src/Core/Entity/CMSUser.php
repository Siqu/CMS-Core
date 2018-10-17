<?php

namespace Siqu\CMS\Core\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Siqu\CMS\Core\Entity\Traits\IdentifiableTrait;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class CMSUser
 * @package Siqu\CMS\Core\Entity
 * @ORM\Entity()
 * @ORM\Table(name="cms_page")
 */
class CMSUser implements UserInterface, \Serializable
{
    const ROLE_DEFAULT = 'ROLE_USER';
    const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';

    use IdentifiableTrait;

    /**
     * @var string|null
     * @ORM\Column(name="username", type="string", length=25, unique=true)
     */
    private $username;

    /**
     * @var string|null
     * @ORM\Column(name="password", type="string", length=64)
     */
    private $password;

    /**
     * @var string|null
     */
    private $plainPassword;

    /**
     * @var string|null
     * @ORM\Column(name="email", type="string", length=254, unique=true)
     */
    private $email;

    /**
     * @var bool
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $enabled;

    /**
     * @var string|null
     * @ORM\Column(name="confirmation_token", type="string")
     */
    private $confirmationToken;

    /**
     * @var \DateTime|null
     * @ORM\Column(name="last_login", type="datetime")
     */
    private $lastLogin;

    /**
     * @var Collection|Group[]
     * @ORM\ManyToMany(targetEntity="Siqu\CMSCore\Entity\Group")
     * @ORM\JoinTable(name="siqu_user_user_group",
     *     joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     */
    private $groups;

    /**
     * @var array
     * @ORM\Column(name="roles", type="array")
     */
    private $roles;

    /**
     * CMSUser constructor.
     */
    public function __construct()
    {
        $this->enabled = false;
        $this->roles = [];
        $this->groups = new ArrayCollection();
    }

    /**
     * Set the username.
     *
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * Retrieve the username.
     *
     * @return null|string
     * @see UserInterface::getUsername()
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * Set the password.
     *
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * Retrieve the password.
     *
     * @return null|string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * Set the plain password.
     *
     * @param string $plainPassword
     */
    public function setPlainPassword(string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }

    /**
     * Retrieve the plain password.
     *
     * @return null|string
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * Set the email.
     *
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * Retrieve the email.
     *
     * @return null|string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Set enabled.
     *
     * @param bool $enabled
     */
    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }

    /**
     * Retrieve enabled.
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * Set confirmation token.
     *
     * @param string $confirmationToken
     */
    public function setConfirmationToken(string $confirmationToken): void
    {
        $this->confirmationToken = $confirmationToken;
    }

    /**
     * Retrieve the confirmation token.
     *
     * @return null|string
     */
    public function getConfirmationToken(): ?string
    {
        return $this->confirmationToken;
    }

    /**
     * Set last login.
     *
     * @param \DateTime $lastLogin
     */
    public function setLastLogin(\DateTime $lastLogin): void
    {
        $this->lastLogin = $lastLogin;
    }

    /**
     * Retrieve last login.
     *
     * @return \DateTime|null
     */
    public function getLastLogin(): ?\DateTime
    {
        return $this->lastLogin;
    }

    /**
     * Retrieve the salt.
     *
     * @return null|string
     * @see UserInterface::getSalt()
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * Set the groups
     * @param Collection $groups
     */
    public function setGroups(Collection $groups): void
    {
        $this->groups = $groups;
    }

    /**
     * Add a group.
     *
     * @param Group $group
     */
    public function addGroup(Group $group): void
    {
        if (!$this->groups->contains($group)) {
            $this->groups->add($group);
        }
    }

    /**
     * Remove a group.
     *
     * @param Group $group
     */
    public function removeGroup(Group $group): void
    {
        if ($this->groups->contains($group)) {
            $this->groups->removeElement($group);
        }
    }

    /**
     * Retrieve the groups.
     *
     * @return Collection|Group[]
     */
    public function getGroups(): Collection
    {
        return $this->groups;
    }

    /**
     * Get all group names.
     *
     * @return array
     */
    public function getGroupNames(): array
    {
        $names = [];

        foreach ($this->groups as $group) {
            $names[] = $group->getName();
        }

        return $names;
    }

    /**
     * Check if the user has a group.
     *
     * @param string $name
     * @return bool
     */
    public function hasGroup(string $name): bool
    {
        return in_array($name, $this->getGroupNames());
    }

    /**
     * Add a specific role.
     *
     * @param string $role
     */
    public function addRole(string $role): void
    {
        $role = strtoupper($role);

        if ($role === static::ROLE_DEFAULT) {
            return;
        }

        if (!in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }
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
     * Retrieve the roles.
     *
     * @return array
     * @see UserInterface::getRoles()
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        foreach ($this->getGroups() as $group) {
            $roles = array_merge($roles, $group->getRoles());
        }

        $roles[] = static::ROLE_DEFAULT;

        return array_values(array_unique($roles));
    }

    /**
     * Check if user has a specific role.
     *
     * @param string $role
     * @return bool
     */
    public function hasRole(string $role): bool
    {
        return in_array(strtoupper($role), $this->getRoles(), true);
    }

    /**
     * Set the roles.
     *
     * @param array $roles
     */
    public function setRoles(array $roles): void
    {
        $this->roles = [];

        foreach ($roles as $role) {
            $this->addRole($role);
        }
    }

    /**
     * Toggle super admin role.
     *
     * @param bool $active
     */
    public function setSuperAdmin(bool $active): void
    {
        if ($active === true) {
            $this->addRole(static::ROLE_SUPER_ADMIN);
        } else {
            $this->removeRole(static::ROLE_SUPER_ADMIN);
        }
    }

    /**
     * Check if user is a super admin.
     *
     * @return bool
     */
    public function isSuperAdmin(): bool
    {
        return $this->hasRole(static::ROLE_SUPER_ADMIN);
    }

    /**
     * Remove sensitive data.
     *
     * @see UserInterface::eraseCredentials()
     */
    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
    }

    /**
     * Serialize the user.
     *
     * @return string
     * @see \Serializable::serialize()
     */
    public function serialize(): string
    {
        return serialize([
            $this->id,
            $this->uuid,
            $this->username,
            $this->password,
            $this->email,
            $this->enabled
        ]);
    }

    /**
     * Unserialize the user.
     *
     * @param string $serialized
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized): void
    {
        list(
            $this->id,
            $this->uuid,
            $this->username,
            $this->password,
            $this->email,
            $this->enabled
            ) = unserialize($serialized, ['allowed_classes' => false]);
    }

    /**
     * Retrieve the username.
     *
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->getUsername();
    }
}