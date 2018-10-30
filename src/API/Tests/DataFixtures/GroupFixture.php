<?php

namespace Siqu\CMS\API\Tests\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Siqu\CMS\Core\Entity\Group;

/**
 * Class GroupFixture
 * @package Siqu\CMS\API\Tests\DataFixtures
 */
class GroupFixture extends Fixture
{
    const NAME = 'name';
    const ROLES = [
        'ROLE_ADMIN'
    ];

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $user = new Group();
        $user->setName(self::NAME);
        $user->setRoles(self::ROLES);

        $manager->persist($user);
        $manager->flush();
    }
}