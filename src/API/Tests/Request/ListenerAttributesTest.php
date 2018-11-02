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
     */
    public function testConstruct(): void
    {
        $this->assertInstanceOf(ListenerAttributes::class, $this->attributes);
    }

    /**
     * Should return value
     */
    public function testIsAcceptActive(): void
    {
        $this->assertFalse($this->attributes->isAcceptActive());

        $attributes = new ListenerAttributes(['accept' => true]);
        $this->assertTrue($attributes->isAcceptActive());
    }

    /**
     * Should return value
     */
    public function testIsAcceptLanguageActive(): void
    {
        $this->assertFalse($this->attributes->isAcceptLanguageActive());

        $attributes = new ListenerAttributes(['accept-language' => true]);
        $this->assertTrue($attributes->isAcceptLanguageActive());
    }

    /**
     * Setup tests.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->attributes = new ListenerAttributes();
    }
}
