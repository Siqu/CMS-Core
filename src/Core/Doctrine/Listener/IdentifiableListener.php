<?php

namespace Siqu\CMS\Core\Doctrine\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Siqu\CMS\Core\Entity\IdentifiableTrait;
use Siqu\CMS\Core\Util\UuidGenerator;

/**
 * Class IdentifiableListener
 * @package Siqu\CMS\Core\Doctrine\Listener
 */
class IdentifiableListener extends AbstractListener
{
    /** @var UuidGenerator */
    private $uuidGenerator;

    /**
     * CMSUserListener constructor.
     * @param UuidGenerator $uuidGenerator
     */
    public function __construct(UuidGenerator $uuidGenerator)
    {
        $this->uuidGenerator = $uuidGenerator;
    }

    /**
     * Pre persist listener for TimestampableTrait objects.
     *
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args): void
    {
        $object = $args->getObject();

        if ($this->shouldTraitObjectBeHandled($object, IdentifiableTrait::class)) {
            /** @var IdentifiableTrait $object */
            $object->setUuid($this->uuidGenerator->generate());
        }
    }

    /**
     * Pre update listener for TimestampableTrait objects.
     *
     * @param LifecycleEventArgs $args
     */
    public function preUpdate(LifecycleEventArgs $args): void
    {
        // nope
    }
}