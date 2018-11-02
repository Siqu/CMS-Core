<?php

namespace Siqu\CMS\API\Tests;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Siqu\CMS\API\Tests\DataFixtures\CMSUserFixture;
use Siqu\CMS\API\Tests\DataFixtures\GroupFixture;
use Siqu\CMS\API\Tests\DataFixtures\PageFixture;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Client;

/**
 * Class FixtureAwareTestCase
 * @package Siqu\CMS\API\Tests
 */
abstract class FixtureAwareTestCase extends WebTestCase
{
    /** @var Client */
    protected $client;

    /**
     * Retrieve the endpoint.
     * @return string
     */
    abstract protected function getEndpoint(): string;

    /**
     * Call delete endpoint.
     *
     * @param string $uuid
     */
    protected function callDelete(string $uuid): void
    {
        $url = '/api/v1/' . $this->getEndpoint();
        if ($uuid) {
            $url .= '/' . $uuid;
        }

        $this->client->request('DELETE', $url, [], [], [
            'HTTP_Accept' => 'application/json'
        ]);
    }

    /**
     * Call the endpoint with get and a optional uuid
     * @param string|null $uuid
     */
    protected function callGet(string $uuid = null): void
    {
        $url = '/api/v1/' . $this->getEndpoint();
        if ($uuid) {
            $url .= '/' . $uuid;
        }

        $this->client->request('GET', $url, [], [], [
            'HTTP_Accept' => 'application/json'
        ]);
    }

    /**
     * Call the patch endpoint.
     *
     * @param string $uuid
     * @param array $data
     */
    protected function callPatch(string $uuid, array $data): void
    {
        $this->client->request('PATCH', '/api/v1/' . $this->getEndpoint() . '/' . $uuid, [], [], [
            'HTTP_Accept' => 'application/json',
            'CONTENT_TYPE' => 'application/json'
        ], json_encode($data));
    }

    /**
     * Call the post endpoint.
     *
     * @param array $data
     */
    protected function callPost(array $data): void
    {
        $this->client->request('POST', '/api/v1/' . $this->getEndpoint(), [], [], [
            'HTTP_Accept' => 'application/json',
            'CONTENT_TYPE' => 'application/json'
        ], json_encode($data));
    }

    /**
     * Retrieve a existing uuid.
     * @return string
     */
    protected function getExistingUuid(): string
    {
        $this->client->request('GET', '/api/v1/' . $this->getEndpoint(), [], [], [
            'HTTP_Accept' => 'application/json'
        ]);

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $data = $this->client->getResponse()->getContent();
        $data = json_decode($data, false);
        $entry = $data[0];

        return $entry->uuid;
    }

    /**
     * Load fixtures.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->client = static::createClient([], [
            'PHP_AUTH_USER' => CMSUserFixture::USERNAME,
            'PHP_AUTH_PW' => CMSUserFixture::PASSWORD
        ]);

        $container = $this->client->getContainer();
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $container->get('doctrine')->getManager();
        $loader = new Loader();
        $loader->addFixture(new CMSUserFixture());
        $loader->addFixture(new GroupFixture());
        $loader->addFixture(new PageFixture());

        $purger = new ORMPurger($entityManager);
        $purger->setPurgeMode(ORMPurger::PURGE_MODE_TRUNCATE);
        $executor = new ORMExecutor($entityManager, $purger);
        $executor->execute($loader->getFixtures());

        $entityManager->clear();

    }
}