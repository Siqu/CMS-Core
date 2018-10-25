<?php

namespace Siqu\CMS\API\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class CMSUserControllerTest
 * @package Siqu\CMS\API\Tests\Controller
 */
class CMSUserControllerTest extends WebTestCase
{
    /**
     * Should list all user.
     */
    public function testIndexAction(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/v1/user');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    /**
     * Setup tests.
     */
    protected function setUp()
    {
        parent::setUp();
    }
}
