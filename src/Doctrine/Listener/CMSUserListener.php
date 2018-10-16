<?php

namespace Siqu\CMS\Core\Doctrine\Listener;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Siqu\CMS\Core\Entity\CMSUser;
use Siqu\CMS\Core\Util\PasswordUpdater;

/**
 * Class CMSUserListener
 * @package Siqu\CMS\Core\Doctrine\Listener
 */
class CMSUserListener implements EventSubscriber
{
    /** @var PasswordUpdater */
    private $passwordUpdater;

    /**
     * CMSUserListener constructor.
     * @param PasswordUpdater $passwordUpdater
     */
    public function __construct(PasswordUpdater $passwordUpdater)
    {
        $this->passwordUpdater = $passwordUpdater;
    }

    /**
     * Retrieve the subscribed events.
     *
     * @return array
     * @see EventSubscriber::getSubscribedEvents()
     */
    public function getSubscribedEvents()
    {
        return [
            'prePersist',
            'preUpdate'
        ];
    }

    public function prePersist(LifecycleEventArgs $args) {
        $object = $args->getObject();

        if($object instanceof CMSUser) {
            $this->updateUserFields($object);
        }
    }

    /**
     * Pre update listener for CMSUser.
     *
     * @param LifecycleEventArgs $args
     */
    public function preUpdate(LifecycleEventArgs $args) {
        $object = $args->getObject();

        if($object instanceof CMSUser) {
            $this->updateUserFields($object);
            $this->recomputeChangeSet($args->getObjectManager(), $object);
        }
    }

    /**
     * Update the user properties.
     *
     * @param CMSUser $user
     */
    private function updateUserFields(CMSUser $user) {
        $this->passwordUpdater->hashPassword($user);
    }

    /**
     * Recomputes change set for doctrine.
     *
     * @param ObjectManager $om
     * @param CMSUser $user
     */
    private function recomputeChangeSet(ObjectManager $om, CMSUser $user) {
        $meta = $om->getClassMetadata(get_class($user));

        $om->getUnitOfWork()->recomputeSingleEntityChangeSet($meta, $user);
    }
}