<?php

namespace Siqu\CMS\Core\Tests\Dummy;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Siqu\CMS\Core\Doctrine\Listener\AbstractListener;

/**
 * Class DummyListener
 * @package Siqu\CMS\Core\Tests\Dummy
 */
class DummyListener extends AbstractListener
{

    /**
     * Pre persist listener
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args): void
    {
        $this->recomputeChangeSet($args->getObjectManager(), $args->getObject());
    }

    /**
     * Pre update listener
     * @param LifecycleEventArgs $args
     */
    public function preUpdate(LifecycleEventArgs $args): void
    {
        $this->recomputeChangeSet($args->getObjectManager(), $args->getObject());
    }
}