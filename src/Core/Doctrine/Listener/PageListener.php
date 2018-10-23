<?php

namespace Siqu\CMS\Core\Doctrine\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Siqu\CMS\Core\Entity\Page;
use Siqu\CMS\Core\Util\Urlizer;

/**
 * Class PageListener
 * @package Siqu\CMS\Core\Doctrine\Listener
 */
class PageListener extends AbstractListener
{
    /** @var Urlizer */
    private $urlizer;

    /**
     * PageListener constructor.
     * @param Urlizer $urlizer
     */
    public function __construct(
        Urlizer $urlizer
    )
    {
        $this->urlizer = $urlizer;
    }

    /**
     * Pre persist listener for CMSUser
     *
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args): void
    {
        $object = $args->getObject();

        if ($object instanceof Page) {
            $this->updateSlug($object);
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

        if ($object instanceof Page) {
            $this->updateSlug($object);
        }
    }

    /**
     * Update the slug of a page.
     *
     * @param Page $object
     */
    private function updateSlug(Page $object): void
    {
        $slug = $this->urlizer->urlize($object->getTitle());

        if($object->getParent()) {
            $slug = $object->getParent()->getSlug() . '/' . $slug;
        }

        $object->setSlug($slug);
    }
}