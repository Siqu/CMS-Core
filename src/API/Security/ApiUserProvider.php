<?php

namespace Siqu\CMS\API\Security;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Siqu\CMS\Core\Entity\CMSUser;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * Class ApiKeyUserProvider
 * @package Siqu\CMS\API\Security
 */
class ApiUserProvider implements UserProviderInterface
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * ApiUserProvider constructor.
     * @param EntityManager $entityManager
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(
        EntityManager $entityManager,
        UserPasswordEncoderInterface $encoder
    )
    {
        $this->entityManager = $entityManager;
        $this->encoder = $encoder;
    }

    /**
     * Read the username for the given credentials
     * @param Credentials $credentials
     * @return string|null
     */
    public function getUsernameForCredentials(Credentials $credentials): ?string
    {
        $user = $this->fetchUser($credentials->getUsername());

        if (!$this->encoder->isPasswordValid($user, $credentials->getPassword())) {
            return null;
        }

        $user->setLastLogin(new \DateTime());

        try {
            $this->entityManager->flush();
        } catch (OptimisticLockException $e) {
        } catch (ORMException $e) {
        }

        return $user->getUsername();
    }

    /**
     * Load the given user.
     *
     * @param string $username
     * @return null|CMSUser
     */
    public function loadUserByUsername($username): ?CMSUser
    {
        return $this->fetchUser($username);
    }

    /**
     * Refresh the current user.
     *
     * @param UserInterface $user
     * @return null|CMSUser|UserInterface
     */
    public function refreshUser(UserInterface $user): ?CMSUser
    {
        if (!$user instanceof CMSUser) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }

        $username = $user->getUsername();

        return $this->fetchUser($username);
    }

    /**
     * Check if user class is supported.
     * @param string $class
     * @return bool
     */
    public function supportsClass($class): bool
    {
        return CMSUser::class === $class;
    }

    /**
     * Load the user by username from the database
     * @param string $username
     * @return null|CMSUser
     * @throws UsernameNotFoundException
     */
    private function fetchUser(string $username): ?CMSUser
    {
        $repository = $this->entityManager->getRepository(CMSUser::class);

        /** @var CMSUser $user */
        $user = $repository->findOneBy([
            'username' => $username
        ]);

        if (!$user) {
            throw new UsernameNotFoundException(
                sprintf('Username "%s" does not exist.', $username)
            );
        }

        return $user;
    }
}