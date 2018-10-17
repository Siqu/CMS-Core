<?php

namespace Siqu\CMS\Core\Util;

use Siqu\CMS\Core\Entity\CMSUser;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

/**
 * Class PasswordUpdater
 * @package Siqu\CMS\Core\Util
 */
class PasswordUpdater
{
    /** @var EncoderFactoryInterface */
    private $encoderFactory;

    /**
     * PasswordUpdater constructor.
     * @param EncoderFactoryInterface $encoderFactory
     */
    public function __construct(EncoderFactoryInterface $encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;
    }

    /**
     * Hash the unencrypted password.
     *
     * @param CMSUser $user
     */
    public function hashPassword(CMSUser $user): void
    {
        $plainPassword = $user->getPlainPassword();

        if (strlen($plainPassword) === 0) {
            return;
        }

        $encoder = $this->encoderFactory->getEncoder($user);

        $hashedPassword = $encoder->encodePassword($plainPassword, $user->getSalt());
        $user->setPassword($hashedPassword);
        $user->eraseCredentials();
    }
}