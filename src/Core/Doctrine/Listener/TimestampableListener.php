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

        if ($this->shouldObjectBeHandled($object)) {
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

        if ($this->shouldObjectBeHandled($object)) {
            /** @var TimestampableTrait $object */
            $object->setUpdatedAt(new \DateTime());
            $this->recomputeChangeSet($args->getObjectManager(), $object);
        }
    }

    /**
     * Check if the object should be handled.
     *
     * @param $object
     * @return bool
     */
    private function shouldObjectBeHandled($object): bool
    {
        // @codeCoverageIgnoreStart
        try {
            $reflection = new \ReflectionClass($object);
        } catch (\ReflectionException $e) {
            return false;
        }
        // @codeCoverageIgnoreEnd

        $traits = $reflection->getTraitNames();

        return in_array(TimestampableTrait::class, $traits);
    }
}