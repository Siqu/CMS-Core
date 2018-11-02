<?php

namespace Siqu\CMS\API\Tests\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Siqu\CMS\Core\Entity\CMSUser;

/**
 * Class CMSUserFixture
 * @package Siqu\CMS\API\Tests\DataFixtures
 */
class CMSUserFixture extends Fixture
{
    const EMAIL = 'mail@mail.test';
    const PASSWORD = 'password';
    const USERNAME = 'username';

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $user = new CMSUser();
        $user->setUsername(self::USERNAME);
        $user->setPlainPassword(self::PASSWORD);
        $user->setEmail(self::EMAIL);

        $manager->persist($user);
        $manager->flush();
    }
}