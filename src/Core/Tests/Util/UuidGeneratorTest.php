<?php

namespace Siqu\CMS\Core\Tests\Util;

use PHPUnit\Framework\TestCase;
use Siqu\CMS\Core\Util\UuidGenerator;

/**
 * Class UuidGeneratorTest
 * @package Siqu\CMS\Core\Tests\Util
 */
class UuidGeneratorTest extends TestCase
{
    /** @var UuidGenerator */
    private $generator;

    /**
     * Should create instance.
     */
    public function testConstruct(): void
    {
        $this->assertInstanceOf(
            UuidGenerator::class,
            $this->generator
        );
    }

    /**
     * Should generate uuid
     */
    public function testGenerate()
    {
        $this->assertRegExp(
            '/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i',
            $this->generator->generate()
        );
    }

    /**
     * Setup tests.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->generator = new UuidGenerator();
    }
}
