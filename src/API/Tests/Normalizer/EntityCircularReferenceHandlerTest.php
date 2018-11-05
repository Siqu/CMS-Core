<?php

namespace Siqu\CMS\API\Tests\Normalizer;

use PHPUnit\Framework\TestCase;
use Siqu\CMS\API\Normalizer\EntityCircularReferenceHandler;
use Siqu\CMS\Core\Tests\Dummy\IdentifiableObject;

/**
 * Class EntityCircularReferenceHandlerTest
 * @package Siqu\CMS\API\Tests\Normalizer
 */
class EntityCircularReferenceHandlerTest extends TestCase
{
    /** @var EntityCircularReferenceHandler */
    private $handler;

    /**
     * Should create correct instance.
     */
    public function testConstruct(): void
    {
        $this->assertInstanceOf(
            EntityCircularReferenceHandler::class,
            $this->handler
        );
    }

    /**
     * Should return array with uuid.
     */
    public function testHandleCorrectClass(): void
    {
        $data = new IdentifiableObject();
        $data->setUuid('uuid');

        $this->assertEquals(
            [
                'uuid' => 'uuid'
            ],
            $this->handler->handle($data)
        );
    }

    /**
     * Should return data as is
     */
    public function testHandleIncorrectClass(): void
    {
        $data = new \stdClass();

        $this->assertEquals(
            $data,
            $this->handler->handle($data)
        );
    }

    /**
     * Should return data as is.
     */
    public function testHandleIncorrectObject(): void
    {
        $data = [];

        $this->assertEquals(
            $data,
            $this->handler->handle($data)
        );
    }

    /**
     * Setup tests.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->handler = new EntityCircularReferenceHandler();
    }
}
