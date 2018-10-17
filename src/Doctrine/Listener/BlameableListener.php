<?php

namespace Siqu\CMS\Core\Doctrine\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Siqu\CMS\Core\Entity\CMSUser;
use Siqu\CMS\Core\Entity\Traits\BlameableTrait;
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

        if ($this->shouldObjectBeHandled($object)) {
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

        if ($this->shouldObjectBeHandled($object)) {
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

        return in_array(BlameableTrait::class, $traits);
    }
}