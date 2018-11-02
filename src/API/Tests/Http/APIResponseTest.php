<?php

namespace Siqu\CMS\API\Tests\Http;

use PHPUnit\Framework\TestCase;
use Siqu\CMS\API\Http\APIResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class APIResponseTest
 * @package Siqu\CMS\API\Tests\Http
 */
class APIResponseTest extends TestCase
{
    /** @var APIResponse */
    private $response;

    /**
     * Should return value from constructor.
     */
    public function testGetData(): void
    {
        $this->assertEquals([], $this->response->getData());
    }

    /**
     * Should create proper instance.
     */
    public function testInstance(): void
    {
        $this->assertInstanceOf(APIResponse::class, $this->response);
        $this->assertEquals('', $this->response->getContent());
        $this->assertEquals(Response::HTTP_OK, $this->response->getStatusCode());
    }

    /**
     * Setup tests.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->response = new APIResponse([]);
    }
}
