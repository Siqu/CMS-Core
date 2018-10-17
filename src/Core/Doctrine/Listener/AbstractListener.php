<?php

namespace Siqu\CMS\Core\Doctrine\Listener;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * Class AbstractListener
 * @package Siqu\CMS\Core\Doctrine\Listener
 */
abstract class AbstractListener implements EventSubscriber
{
    /**
     * Recomputes change set for doctrine.
     *
     * @param ObjectManager $om
     * @param $object
     */
    protected function recomputeChangeSet(ObjectManager $om, $object): void {
        $meta = $om->getClassMetadata(get_class($object));

        $om->getUnitOfWork()->recomputeSingleEntityChangeSet($meta, $object);
    }

    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return string[]
     */
    public function getSubscribedEvents()
    {
        return [
            'prePersist',
            'preUpdate'
        ];
    }

    /**
     * Pre persist listener
     * @param LifecycleEventArgs $args
     */
    abstract public function prePersist(LifecycleEventArgs $args): void;

    /**
     * Pre update listener
     * @param LifecycleEventArgs $args
     */
    abstract public function preUpdate(LifecycleEventArgs $args): void;
}