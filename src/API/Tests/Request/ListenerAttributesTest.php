<?php

namespace Siqu\CMS\API\Tests\Request;

use PHPUnit\Framework\TestCase;
use Siqu\CMS\API\Request\ListenerAttributes;

/**
 * Class ListenerAttributesTest
 * @package Siqu\CMS\API\Tests\Request
 */
class ListenerAttributesTest extends TestCase
{
    /** @var ListenerAttributes */
    private $attributes;

    /**
     * Should create instance and return default values
     * @throws \ReflectionException
     */
    public function testConstruct(): void
    {
        $this->assertInstanceOf(
            ListenerAttributes::class,
            $this->attributes
        );

        $reflection = new \ReflectionClass($this->attributes);

        $constants = $reflection->getConstants();

        $this->assertCount(4, $constants);
        $this->assertArrayHasKey('API_EXCEPTION_LISTENER', $constants);
        $this->assertArrayHasKey('ACCEPT_LISTENER', $constants);
        $this->assertArrayHasKey('ACCEPT_LANGUAGE_LISTENER', $constants);
        $this->assertArrayHasKey('RESPONSE_FORMATTER_LISTENER', $constants);
    }

    /**
     * Should return true.
     */
    public function testIsListenerActive(): void
    {
        $this->assertTrue($this->attributes->isListenerActive(ListenerAttributes::RESPONSE_FORMATTER_LISTENER));
    }

    /**
     * Should return false.
     */
    public function testIsListenerActiveUnknown(): void
    {
        $this->assertFalse($this->attributes->isListenerActive('unknown'));
    }

    /**
     * Setup tests.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->attributes = new ListenerAttributes(
            [
                ListenerAttributes::RESPONSE_FORMATTER_LISTENER => true
            ]
        );
    }
}
