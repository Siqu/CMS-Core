<?php

namespace Siqu\CMSCore\Tests\Util;

use PHPUnit\Framework\TestCase;
use Siqu\CMSCore\Util\TokenGenerator;

/**
 * Class TokenGeneratorTest
 * @package Siqu\CMSCore\Tests\Util
 */
class TokenGeneratorTest extends TestCase
{
    /** @var TokenGenerator */
    private $generator;

    /**
     * Should create instance
     */
    public function testConstruct() {
        $this->assertInstanceOf(TokenGenerator::class, $this->generator);
    }

    /**
     * Should create token
     */
    public function testGenerateToken()
    {
        $token = $this->generator->generateToken();

        $this->assertEquals(43, strlen($token));
    }

    /**
     * Setup tests.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->generator = new TokenGenerator();
    }
}
