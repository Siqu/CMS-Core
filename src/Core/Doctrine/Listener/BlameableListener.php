<?php

namespace Siqu\CMS\Core\Doctrine\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Siqu\CMS\Core\Entity\BlameableTrait;
use Siqu\CMS\Core\Entity\CMSUser;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * Class BlameableListener
 * @package Siqu\CMS\Core\Doctrine\Listener
 */
class BlameableListener extends AbstractListener
{
    /** @var TokenStorage */
    private $tokenStorage;

    /**
     * BlameableListener constructor.
     * @param TokenStorage $tokenStorage
     */
    public function __construct(TokenStorage $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * Pre persist listener for TimestampableTrait objects.
     *
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args): void
    {
        $object = $args->getObject();

        if ($this->shouldTraitObjectBeHandled($object, BlameableTrait::class)) {
            /** @var BlameableTrait $object */
            $user = $this->getUser();

            if ($user) {
                $object->setUser($user);
            }
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

        if ($this->shouldTraitObjectBeHandled($object, BlameableTrait::class)) {
            /** @var BlameableTrait $object */
            $user = $this->getUser();

            if ($user) {
                $object->setChangeUser($user);
            }
            $this->recomputeChangeSet($args->getObjectManager(), $object);
        }
    }

    /**
     * Retrieve the currently logged in user.
     *
     * @return null|CMSUser
     */
    private function getUser(): ?CMSUser
    {
        $token = $this->tokenStorage->getToken();

        if (!$token) {
            return null;
        }

        if (!\is_object($user = $token->getUser())) {
            return null;
        }

        return $user;
    }
}