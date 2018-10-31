<?php

namespace Siqu\CMS\Core\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Siqu\CMS\Core\Entity\Traits\IdentifiableTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class CMSUser
 * @package Siqu\CMS\Core\Entity
 * @ORM\Entity()
 * @ORM\Table(name="cms_user")
 * @UniqueEntity("username", groups={"new", "update"})
 * @UniqueEntity("email", groups={"new", "update"})
 */
class CMSUser implements AdvancedUserInterface, \Serializable
{
    const ROLE_DEFAULT = 'ROLE_USER';
    const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';

    use IdentifiableTrait;
    /**
     * @var bool
     * @ORM\Column(name="account_non_expired", type="boolean")
     * @Groups({"api"})
     */
    private $accountNonExpired;
    /**
     * @var bool
     * @ORM\Column(name="account_non_locked", type="boolean")
     * @Groups({"api"})
     */
    private $accountNonLocked;
    /**
     * @var string|null
     * @ORM\Column(name="confirmation_token", type="string", nullable=true)
     * @Groups({"api"})
     */
    private $confirmationToken;
    /**
     * @var bool
     * @ORM\Column(name="credentials_non_expired", type="boolean")
     * @Groups({"api"})
     */
    private $credentialsNonExpired;
    /**
     * @var string|null
     * @ORM\Column(name="email", type="string", length=254, unique=true)
     * @Groups({"api"})
     * @Assert\NotBlank(groups={"new"})
     * @Assert\Email(groups={"new", "update"})
     */
    private $email;
    /**
     * @var bool
     * @ORM\Column(name="is_active", type="boolean")
     * @Groups({"api"})
     */
    private $enabled;
    /**
     * @var Collection|Group[]
     * @ORM\ManyToMany(targetEntity="Siqu\CMS\Core\Entity\Group")
     * @ORM\JoinTable(name="siqu_user_user_group",
     *     joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     * @Groups({"api"})
     */
    private $groups;
    /**
     * @var \DateTime|null
     * @ORM\Column(name="last_login", type="datetime", nullable=true)
     * @Groups({"api"})
     */
    private $lastLogin;
    /**
     * @var string|null
     * @ORM\Column(name="password", type="string")
     */
    private $password;
    /**
     * @var string|null
     * @Assert\NotBlank(groups={"new"})
     */
    private $plainPassword;
    /**
     * @var array
     * @ORM\Column(name="roles", type="array")
     * @Groups({"api"})
     */
    private $roles;
    /**
     * @var string|null
     * @ORM\Column(name="username", type="string", length=25, unique=true)
     * @Groups({"api"})
     * @Assert\NotBlank(groups={"new"})
     */
    private $username;

    /**
     * CMSUser constructor.
     */
    public function __construct()
    {
        $this->enabled = true;
        $this->accountNonExpired = true;
        $this->credentialsNonExpired = true;
        $this->accountNonLocked = true;
        $this->roles = [];
        $this->groups = new ArrayCollection();
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

    /**
     * Add a group.
     *
     * @param Group $group
     */
    public function addGroup($group): void
    {
        if (!$this->groups->contains($group)) {
            $this->groups->add($group);
        }
    }

    /**
     * Add a specific role.
     *
     * @param string $role
     */
    public function addRole($role): void
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
     * Remove sensitive data.
     *
     * @see UserInterface::eraseCredentials()
     */
    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
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
     * Retrieve the email.
     *
     * @return null|string
     */
    public function getEmail(): ?string
    {
        return $this->email;
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
     * Retrieve the groups.
     *
     * @return Collection|Group[]
     */
    public function getGroups(): Collection
    {
        return $this->groups;
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
     * Retrieve the password.
     *
     * @return null|string
     */
    public function getPassword(): ?string
    {
        return $this->password;
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
     * Check if the user has a group.
     *
     * @param string $name
     * @return bool
     */
    public function hasGroup($name): bool
    {
        return in_array($name, $this->getGroupNames());
    }

    /**
     * Check if user has a specific role.
     *
     * @param string $role
     * @return bool
     */
    public function hasRole($role): bool
    {
        return in_array(strtoupper($role), $this->getRoles(), true);
    }

    /**
     * Checks whether the user's account has expired.
     *
     * Internally, if this method returns false, the authentication system
     * will throw an AccountExpiredException and prevent login.
     *
     * @return bool true if the user's account is non expired, false otherwise
     *
     * @see AccountExpiredException
     */
    public function isAccountNonExpired(): bool
    {
        return $this->accountNonExpired;
    }

    /**
     * Checks whether the user is locked.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a LockedException and prevent login.
     *
     * @return bool true if the user is not locked, false otherwise
     *
     * @see LockedException
     */
    public function isAccountNonLocked(): bool
    {
        return $this->accountNonLocked;
    }

    /**
     * Checks whether the user's credentials (password) has expired.
     *
     * Internally, if this method returns false, the authentication system
     * will throw a CredentialsExpiredException and prevent login.
     *
     * @return bool true if the user's credentials are non expired, false otherwise
     *
     * @see CredentialsExpiredException
     */
    public function isCredentialsNonExpired(): bool
    {
        return $this->credentialsNonExpired;
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
     * Check if user is a super admin.
     *
     * @return bool
     */
    public function isSuperAdmin(): bool
    {
        return $this->hasRole(static::ROLE_SUPER_ADMIN);
    }

    /**
     * Remove a group.
     *
     * @param Group $group
     */
    public function removeGroup($group): void
    {
        if ($this->groups->contains($group)) {
            $this->groups->removeElement($group);
        }
    }

    /**
     * Remove a specific role.
     *
     * @param string $role
     */
    public function removeRole($role): void
    {
        if ($this->hasRole($role)) {
            $key = array_search(strtoupper($role), $this->roles, true);
            unset($this->roles[$key]);
            $this->roles = array_values($this->roles);
        }
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
     * @param bool $accountNonExpired
     */
    public function setAccountNonExpired(bool $accountNonExpired): void
    {
        $this->accountNonExpired = $accountNonExpired;
    }

    /**
     * @param bool $accountNonLocked
     */
    public function setAccountNonLocked(bool $accountNonLocked): void
    {
        $this->accountNonLocked = $accountNonLocked;
    }

    /**
     * Set confirmation token.
     *
     * @param string $confirmationToken
     */
    public function setConfirmationToken($confirmationToken): void
    {
        $this->confirmationToken = $confirmationToken;
    }

    /**
     * @param bool $credentialsNonExpired
     */
    public function setCredentialsNonExpired(bool $credentialsNonExpired): void
    {
        $this->credentialsNonExpired = $credentialsNonExpired;
    }

    /**
     * Set the email.
     *
     * @param string $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * Set enabled.
     *
     * @param bool $enabled
     */
    public function setEnabled($enabled): void
    {
        $this->enabled = $enabled;
    }

    /**
     * Set the groups
     * @param Collection $groups
     */
    public function setGroups($groups): void
    {
        $this->groups = $groups;
    }

    /**
     * Set last login.
     *
     * @param \DateTime $lastLogin
     */
    public function setLastLogin($lastLogin): void
    {
        $this->lastLogin = $lastLogin;
    }

    /**
     * Set the password.
     *
     * @param string $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }

    /**
     * Set the plain password.
     *
     * @param string $plainPassword
     */
    public function setPlainPassword($plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }

    /**
     * Set the roles.
     *
     * @param array $roles
     */
    public function setRoles($roles): void
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
    public function setSuperAdmin($active): void
    {
        if ($active === true) {
            $this->addRole(static::ROLE_SUPER_ADMIN);
        } else {
            $this->removeRole(static::ROLE_SUPER_ADMIN);
        }
    }

    /**
     * Set the username.
     *
     * @param string $username
     */
    public function setUsername($username): void
    {
        $this->username = $username;
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
}