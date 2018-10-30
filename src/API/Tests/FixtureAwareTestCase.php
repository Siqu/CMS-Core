<?php

namespace Siqu\CMS\API\Tests;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Siqu\CMS\API\Tests\DataFixtures\CMSUserFixture;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Client;

/**
 * Class FixtureAwareTestCase
 * @package Siqu\CMS\API\Tests
 */
class FixtureAwareTestCase extends WebTestCase
{
    /** @var Client */
    protected $client;

    protected function setUp()
    {
        parent::setUp();

        $this->client = static::createClient();

        $container = $this->client->getContainer();
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $container->get('doctrine')->getManager();
        $loader = new Loader();
        $loader->addFixture(new CMSUserFixture());

        $purger = new ORMPurger($entityManager);
        $executor = new ORMExecutor($entityManager, $purger);
        $executor->execute($loader->getFixtures());

    }
}