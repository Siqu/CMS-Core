<?php

namespace Siqu\CMS\API\Tests\Integration;

use Siqu\CMS\API\Tests\DataFixtures\CMSUserFixture;
use Siqu\CMS\API\Tests\FixtureAwareTestCase;

/**
 * Class CMSUserControllerTest
 * @package Siqu\CMS\API\Tests\Integration
 */
class CMSUserControllerTest extends FixtureAwareTestCase
{
    /**
     * Should list all user.
     */
    public function testIndex(): void
    {
        $this->callGet();

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $data = $this->client->getResponse()->getContent();
        $data = json_decode($data, false);

        $this->assertCount(1, $data);

        $user = $data[0];
        $this->assertEquals(CMSUserFixture::USERNAME, $user->username);
        $this->assertEquals(CMSUserFixture::EMAIL, $user->email);
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
     * Should return 200 and user
     */
    public function testShow(): void
    {
        $uuid = $this->getExistingUuid();

        $this->callGet($uuid);

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $data = $this->client->getResponse()->getContent();
        $user = json_decode($data, false);
        $this->assertEquals(CMSUserFixture::USERNAME, $user->username);
        $this->assertEquals(CMSUserFixture::EMAIL, $user->email);
        $this->assertEquals($uuid, $user->uuid);
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

        $this->assertCount(3, $errors);
        $this->assertEquals('email', $errors[0]->path);
        $this->assertEquals('plainPassword', $errors[1]->path);
        $this->assertEquals('username', $errors[2]->path);

        $this->callPost([
            'email' => 'invalid',
            'username' => CMSUserFixture::USERNAME,
            'plainpassword' => 'password'
        ]);
        $data = $this->client->getResponse()->getContent();
        $errors = json_decode($data, false);

        $this->assertCount(2, $errors);
        $this->assertEquals('username', $errors[0]->path);
        $this->assertEquals('email', $errors[1]->path);

        $this->callPost([
            'email' => CMSUserFixture::EMAIL,
            'username' => 'username2',
            'plainpassword' => 'password'
        ]);
        $data = $this->client->getResponse()->getContent();
        $errors = json_decode($data, false);

        $this->assertCount(1, $errors);
        $this->assertEquals('email', $errors[0]->path);
    }

    /**
     * Should return 201 and user
     */
    public function testCreate(): void
    {
        $this->callPost([
            'email' => 'mail2@mail2.test',
            'username' => 'username2',
            'plainpassword' => 'password'
        ]);

        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
        $data = $this->client->getResponse()->getContent();
        $user = json_decode($data, false);

        $this->assertEquals('username2', $user->username);
        $this->assertEquals('mail2@mail2.test', $user->email);
        $this->assertNotNull($user->uuid);
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

        $uuid = $this->getExistingUuid();
        $this->callPatch($uuid, [
            'email' => 'invalid'
        ]);

        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $data = $this->client->getResponse()->getContent();
        $errors = json_decode($data, false);

        $this->assertCount(1, $errors);
        $this->assertEquals('email', $errors[0]->path);
    }

    /**
     * Should return 200 and updated user
     */
    public function testUpdate(): void
    {
        $uuid = $this->getExistingUuid();
        $this->callPatch($uuid, [
            'email' => 'mail2@mail2.test'
        ]);

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $data = $this->client->getResponse()->getContent();
        $user = json_decode($data, false);

        $this->assertEquals('mail2@mail2.test', $user->email);
    }

    /**
     * Retrieve the endpoint.
     *
     * @return string
     */
    protected function getEndpoint(): string
    {
        return 'user';
    }

    /**
     * Setup tests.
     */
    protected function setUp()
    {
        parent::setUp();
    }
}
