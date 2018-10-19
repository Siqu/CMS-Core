<?php

namespace Siqu\CMS\Core\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Siqu\CMS\Core\Entity\CMSUser;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Class BlameableTrait
 * @package Siqu\CMS\Core\Entity\Traits
 */
trait BlameableTrait
{
    /**
     * @var CMSUser|null
     * @ORM\ManyToOne(targetEntity="Siqu\CMS\Core\Entity\CMSUser")
     * @ORM\JoinColumn(name="change_user_id", referencedColumnName="id")
     * @Groups({"api"})
     */
    private $changeUser;
    /**
     * @var CMSUser|null
     * @ORM\ManyToOne(targetEntity="Siqu\CMS\Core\Entity\CMSUser")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @Groups({"api"})
     */
    private $user;

    /**
     * @return null|CMSUser
     */
    public function getChangeUser(): ?CMSUser
    {
        return $this->changeUser;
    }

    /**
     * @return null|CMSUser
     */
    public function getUser(): ?CMSUser
    {
        return $this->user;
    }

    /**
     * @param CMSUser $changeUser
     */
    public function setChangeUser(CMSUser $changeUser): void
    {
        $this->changeUser = $changeUser;
    }

    /**
     * @param CMSUser $user
     */
    public function setUser(CMSUser $user): void
    {
        $this->user = $user;
    }
}