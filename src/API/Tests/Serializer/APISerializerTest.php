<?php

namespace Siqu\CMS\API\Tests\Serializer;

use PHPUnit\Framework\TestCase;
use Siqu\CMS\API\Serializer\APISerializer;

/**
 * Class APISerializerTest
 * @package Siqu\CMS\API\Tests\Serializer
 */
class APISerializerTest extends TestCase
{
    /** @var APISerializer */
    private $serializer;

    /**
     * Should create correct instance.
     */
    public function testConstruct(): void
    {
        $this->assertInstanceOf(
            APISerializer::class,
            $this->serializer
        );
    }

    /**
     * Setup tests.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->serializer = new APISerializer();
    }
}
