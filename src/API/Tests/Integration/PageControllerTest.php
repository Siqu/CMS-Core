<?php

namespace Siqu\CMS\API\Tests\Integration;

use Siqu\CMS\API\Tests\DataFixtures\PageFixture;
use Siqu\CMS\API\Tests\FixtureAwareTestCase;

/**
 * Class PageControllerTest
 * @package Siqu\CMS\API\Tests\Integration
 */
class PageControllerTest extends FixtureAwareTestCase
{
    /**
     * Should list all pages.
     */
    public function testIndex(): void
    {
        $this->callGet();

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $data = $this->client->getResponse()->getContent();
        $data = json_decode($data, false);

        $this->assertCount(1, $data);

        $page = $data[0];
        $this->assertEquals(PageFixture::TITLE, $page->title);
    }

    /**
     * Should return 404
     */
    public function testShowNonExisting(): void
    {
        $this->callGet('1');

        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
    }

    /**
     * Should return 200 and page
     */
    public function testShow(): void
    {
        $uuid = $this->getExistingUuid();

        $this->callGet($uuid);

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $data = $this->client->getResponse()->getContent();
        $page = json_decode($data, false);
        $this->assertEquals(PageFixture::TITLE, $page->title);;
        $this->assertEquals($uuid, $page->uuid);
    }

    /**
     * Should return 400 and error messages.
     */
    public function testCreateInvalid(): void
    {
        $this->callPost([]);

        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $data = $this->client->getResponse()->getContent();
        $errors = json_decode($data, false);

        $this->assertCount(1, $errors);
        $this->assertEquals('title', $errors[0]->path);

        $this->callPost([
            'title' => 'title'
        ]);

        $this->assertCount(1, $errors);
        $this->assertEquals('title', $errors[0]->path);
    }

    /**
     * Should return 201 and page
     */
    public function testCreate(): void
    {
        $this->callPost([
            'title' => 'title2'
        ]);

        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
        $data = $this->client->getResponse()->getContent();
        $page = json_decode($data, false);

        $this->assertEquals('title2', $page->title);
        $this->assertNotNull($page->uuid);
    }

    /**
     * Should return 404
     */
    public function testDeleteInvalid(): void
    {
        $this->callDelete('invalid');

        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
    }

    /**
     * Should return 404
     */
    public function testDelete(): void
    {
        $uuid = $this->getExistingUuid();
        $this->callDelete($uuid);

        $this->assertEquals(204, $this->client->getResponse()->getStatusCode());
    }

    /**
     * Should return 404
     */
    public function testUpdateInvalid(): void
    {
        $this->callPatch('invalid', []);

        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
    }

    /**
     * Should return 200 and updated page
     */
    public function testUpdate(): void
    {
        $uuid = $this->getExistingUuid();
        $this->callPatch($uuid, [
            'title' => 'title2'
        ]);

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $data = $this->client->getResponse()->getContent();
        $page = json_decode($data, false);

        $this->assertEquals('title2', $page->title);
    }

    /**
     * Retrieve the endpoint.
     *
     * @return string
     */
    protected function getEndpoint(): string
    {
        return 'page';
    }

    /**
     * Setup tests.
     */
    protected function setUp()
    {
        parent::setUp();
    }
}
