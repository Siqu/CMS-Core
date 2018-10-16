<?php

namespace Siqu\CMSCore\Util;

use Siqu\CMSCore\Entity\CMSUser;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

/**
 * Class PasswordUpdater
 * @package Siqu\CMSCore\Util
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
    public function hashPassword(CMSUser $user) {
        $plainPassword = $user->getPlainPassword();

        if(strlen($plainPassword) === 0) {
            return;
        }

        $encoder = $this->encoderFactory->getEncoder($user);

        $hashedPassword = $encoder->encodePassword($plainPassword, $user->getSalt());
        $user->setPassword($hashedPassword);
        $user->eraseCredentials();
    }
}