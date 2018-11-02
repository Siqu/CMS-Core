<?php

namespace Siqu\CMS\API\Tests\Integration;

use Siqu\CMS\API\Controller\APIController;
use Siqu\CMS\API\Controller\GroupController;
use Siqu\CMS\API\Tests\DataFixtures\GroupFixture;
use Siqu\CMS\API\Tests\FixtureAwareTestCase;

/**
 * Class GroupControllerTest
 * @package Siqu\CMS\API\Tests\Integration
 */
class GroupControllerTest extends FixtureAwareTestCase
{
    /**
     * Should return 201 and group
     * @APIController::create
     * @GroupController::create
     */
    public function testCreate(): void
    {
        $this->callPost([
            'name' => 'name2',
            'roles' => [
                'ROLE_TEST'
            ]
        ]);

        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
        $data = $this->client->getResponse()->getContent();
        $group = json_decode($data, false);

        $this->assertEquals('name2', $group->name);
        $this->assertEquals([
            'ROLE_TEST'
        ], $group->roles);
        $this->assertNotNull($group->uuid);
    }

    /**
     * Should return 400 and error messages.
     * @APIController::create
     * @GroupController::create
     */
    public function testCreateInvalid(): void
    {
        $this->callPost([]);

        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $data = $this->client->getResponse()->getContent();
        $errors = json_decode($data, false);

        $this->assertCount(1, $errors);
        $this->assertEquals('name', $errors[0]->path);
    }

    /**
     * Should return 404
     * @APIController::delete
     * @GroupController::delete
     */
    public function testDelete(): void
    {
        $uuid = $this->getExistingUuid();
        $this->callDelete($uuid);

        $this->assertEquals(204, $this->client->getResponse()->getStatusCode());
    }

    /**
     * Should return 404
     * @APIController::delete
     * @GroupController::delete
     */
    public function testDeleteInvalid(): void
    {
        $this->callDelete('invalid');

        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
    }

    /**
     * Should list all groups.
     * @APIController::index
     * @GroupController::index
     */
    public function testIndex(): void
    {
        $this->callGet();

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $data = $this->client->getResponse()->getContent();
        $data = json_decode($data, false);

        $this->assertCount(1, $data);

        $group = $data[0];
        $this->assertEquals(GroupFixture::NAME, $group->name);
        $this->assertEquals(GroupFixture::ROLES, $group->roles);
    }

    /**
     * Should return 200 and group
     * @APIController::show
     * @GroupController::show
     */
    public function testShow(): void
    {
        $uuid = $this->getExistingUuid();

        $this->callGet($uuid);

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $data = $this->client->getResponse()->getContent();
        $group = json_decode($data, false);
        $this->assertEquals(GroupFixture::NAME, $group->name);
        $this->assertEquals(GroupFixture::ROLES, $group->roles);
        $this->assertEquals($uuid, $group->uuid);
    }

    /**
     * Should return 404
     * @APIController::show
     * @GroupController::show
     */
    public function testShowNonExisting(): void
    {
        $this->callGet('1');

        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
    }

    /**
     * Should return 200 and updated $group
     * @APIController::update
     * @GroupController::update
     */
    public function testUpdate(): void
    {
        $uuid = $this->getExistingUuid();
        $this->callPatch($uuid, [
            'name' => 'name2'
        ]);

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $data = $this->client->getResponse()->getContent();
        $group = json_decode($data, false);

        $this->assertEquals('name2', $group->name);
    }

    /**
     * Should return 404
     * @APIController::update
     * @GroupController::update
     */
    public function testUpdateInvalid(): void
    {
        $this->callPatch('invalid', []);

        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
    }

    /**
     * Retrieve the endpoint.
     *
     * @return string
     */
    protected function getEndpoint(): string
    {
        return 'group';
    }

    /**
     * Setup tests.
     */
    protected function setUp()
    {
        parent::setUp();
    }
}
