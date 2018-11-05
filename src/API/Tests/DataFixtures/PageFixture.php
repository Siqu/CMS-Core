<?php

namespace Siqu\CMS\API\Tests\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Siqu\CMS\Core\Entity\Page;

/**
 * Class PageFixture
 * @package Siqu\CMS\API\Tests\DataFixtures
 */
class PageFixture extends Fixture
{
    const TITLE = 'title';

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $user = new Page();
        $user->setTitle(self::TITLE);

        $manager->persist($user);
        $manager->flush();
    }
}
