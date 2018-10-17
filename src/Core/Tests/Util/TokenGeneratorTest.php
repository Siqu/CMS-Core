<?php

namespace Siqu\CMS\Core\Tests\Util;

use PHPUnit\Framework\TestCase;
use Siqu\CMS\Core\Util\TokenGenerator;

/**
 * Class TokenGeneratorTest
 * @package Siqu\CMS\Core\Tests\Util
 */
class TokenGeneratorTest extends TestCase
{
    /** @var TokenGenerator */
    private $generator;

    /**
     * Should create instance
     */
    public function testConstruct(): void
    {
        $this->assertInstanceOf(TokenGenerator::class, $this->generator);
    }

    /**
     * Should create token
     */
    public function testGenerateToken(): void
    {
        $token = $this->generator->generateToken();

        $this->assertEquals(43, strlen($token));
    }

    /**
     * Setup tests.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->generator = new TokenGenerator();
    }
}
