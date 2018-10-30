<?php

namespace Siqu\CMS\API\Tests\Controller;

use Siqu\CMS\API\Tests\DataFixtures\CMSUserFixture;
use Siqu\CMS\API\Tests\FixtureAwareTestCase;

/**
 * Class CMSUserControllerTest
 * @package Siqu\CMS\API\Tests\Controller
 */
class CMSUserControllerTest extends FixtureAwareTestCase
{
    /**
     * Should list all user.
     */
    public function testIndex(): void
    {
        $this->client->request('GET', '/api/v1/user', [], [], [
            'HTTP_Accept' => 'application/json'
        ]);

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
        $this->client->request('GET', '/api/v1/user/1', [], [], [
            'HTTP_Accept' => 'application/json'
        ]);

        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
    }

    /**
     * Should return 200 and user
     */
    public function testShow(): void
    {
        $uuid = $this->getExistingUserUuid();

        $this->client->request('GET', '/api/v1/user/' . $uuid, [], [], [
            'HTTP_Accept' => 'application/json'
        ]);

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
        $this->client->request('POST', '/api/v1/user', [], [], [
            'HTTP_Accept' => 'application/json',
            'CONTENT_TYPE' => 'application/json'
        ], '{}');

        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $data = $this->client->getResponse()->getContent();
        $errors = json_decode($data, false);

        $this->assertCount(3, $errors);
        $this->assertEquals('email', $errors[0]->path);
        $this->assertEquals('plainPassword', $errors[1]->path);
        $this->assertEquals('username', $errors[2]->path);

        $this->client->request('POST', '/api/v1/user', [], [], [
            'HTTP_Accept' => 'application/json',
            'CONTENT_TYPE' => 'application/json'
        ], '{"email": "invalid", "username": "' . CMSUserFixture::USERNAME . '", "plainpassword": "password"}');
        $data = $this->client->getResponse()->getContent();
        $errors = json_decode($data, false);

        $this->assertCount(2, $errors);
        $this->assertEquals('username', $errors[0]->path);
        $this->assertEquals('email', $errors[1]->path);

        $this->client->request('POST', '/api/v1/user', [], [], [
            'HTTP_Accept' => 'application/json',
            'CONTENT_TYPE' => 'application/json'
        ], '{"email": "' . CMSUserFixture::EMAIL . '", "username": "username2", "plainpassword": "password"}');
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
        $this->client->request('POST', '/api/v1/user', [], [], [
            'HTTP_Accept' => 'application/json',
            'CONTENT_TYPE' => 'application/json'
        ], '{"email": "mail2@mail2.test", "username": "username2", "plainpassword": "password"}');

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
        $this->client->request('DELETE', '/api/v1/user/invalid', [], [], [
            'HTTP_Accept' => 'application/json'
        ]);

        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
    }

    /**
     * Should return 404
     */
    public function testDelete(): void
    {
        $uuid = $this->getExistingUserUuid();
        $this->client->request('DELETE', '/api/v1/user/' . $uuid, [], [], [
            'HTTP_Accept' => 'application/json'
        ]);

        $this->assertEquals(204, $this->client->getResponse()->getStatusCode());
    }

    /**
     * Should return 404
     */
    public function testUpdateInvalid(): void
    {
        $this->client->request('PATCH', '/api/v1/user/invalid', [], [], [
            'HTTP_Accept' => 'application/json',
            'CONTENT_TYPE' => 'application/json'
        ], '{"email": "mail2@mail2.test", "username": "username2", "plainpassword": "password"}');

        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
    }

    /**
     * Should return 200 and updated user
     */
    public function testUpdate(): void
    {
        $uuid = $this->getExistingUserUuid();
        $this->client->request('PATCH', '/api/v1/user/' . $uuid, [], [], [
            'HTTP_Accept' => 'application/json',
            'CONTENT_TYPE' => 'application/json'
        ], '{"email": "mail2@mail2.test"}');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $data = $this->client->getResponse()->getContent();
        $user = json_decode($data, false);

        $this->assertEquals('mail2@mail2.test', $user->email);
    }

    /**
     * Retrieve a existing uuid.
     * @return string
     */
    private function getExistingUserUuid(): string
    {
        $this->client->request('GET', '/api/v1/user', [], [], [
            'HTTP_Accept' => 'application/json'
        ]);

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $data = $this->client->getResponse()->getContent();
        $data = json_decode($data, false);
        $existingUser = $data[0];

        return $existingUser->uuid;
    }

    /**
     * Setup tests.
     */
    protected function setUp()
    {
        parent::setUp();
    }
}
