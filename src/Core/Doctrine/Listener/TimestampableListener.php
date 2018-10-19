<?php

namespace Siqu\CMS\Core\Doctrine\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Siqu\CMS\Core\Entity\Traits\TimestampableTrait;

/**
 * Class TimestampableListener
 * @package Siqu\CMS\Core\Doctrine\Listener
 */
class TimestampableListener extends AbstractListener
{
    /**
     * Pre persist listener for TimestampableTrait objects.
     *
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args): void
    {
        $object = $args->getObject();

        if ($this->shouldTraitObjectBeHandled($object, TimestampableTrait::class)) {
            /** @var TimestampableTrait $object */
            $object->setCreatedAt(new \DateTime());
        }
    }

    /**
     * Pre update listener for TimestampableTrait objects.
     *
     * @param LifecycleEventArgs $args
     */
    public function preUpdate(LifecycleEventArgs $args): void
    {
        $object = $args->getObject();

        if ($this->shouldTraitObjectBeHandled($object, TimestampableTrait::class)) {
            /** @var TimestampableTrait $object */
            $object->setUpdatedAt(new \DateTime());
            $this->recomputeChangeSet($args->getObjectManager(), $object);
        }
    }
}