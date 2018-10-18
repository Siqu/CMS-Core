<?php

namespace Siqu\CMS\Core\Doctrine\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Siqu\CMS\Core\Entity\CMSUser;
use Siqu\CMS\Core\Util\PasswordUpdater;
use Siqu\CMS\Core\Util\UuidGenerator;

/**
 * Class CMSUserListener
 * @package Siqu\CMS\Core\Doctrine\Listener
 */
class CMSUserListener extends AbstractListener
{
    /** @var PasswordUpdater */
    private $passwordUpdater;

    /** @var UuidGenerator */
    private $uuidGenerator;

    /**
     * CMSUserListener constructor.
     * @param PasswordUpdater $passwordUpdater
     * @param UuidGenerator $uuidGenerator
     */
    public function __construct(PasswordUpdater $passwordUpdater, UuidGenerator $uuidGenerator)
    {
        $this->passwordUpdater = $passwordUpdater;
        $this->uuidGenerator = $uuidGenerator;
    }

    /**
     * Pre persist listener for CMSUser
     *
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args): void
    {
        $object = $args->getObject();

        if ($object instanceof CMSUser) {
            $this->updateUserFields($object);
            $object->setUuid($this->uuidGenerator->generate());
        }
    }

    /**
     * Pre update listener for CMSUser.
     *
     * @param LifecycleEventArgs $args
     */
    public function preUpdate(LifecycleEventArgs $args): void
    {
        $object = $args->getObject();

        if ($object instanceof CMSUser) {
            $this->updateUserFields($object);
            $this->recomputeChangeSet($args->getObjectManager(), $object);
        }
    }

    /**
     * Update the user properties.
     *
     * @param CMSUser $user
     */
    private function updateUserFields(CMSUser $user): void
    {
        $this->passwordUpdater->hashPassword($user);
    }
}