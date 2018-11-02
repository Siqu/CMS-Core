<?php

namespace Siqu\CMS\API\Tests;

use PHPUnit\Framework\TestCase;
use Siqu\CMS\API\CMSAPIBundle;

/**
 * Class CMSAPIBundleTest
 * @package Siqu\CMS\API\Tests
 */
class CMSAPIBundleTest extends TestCase
{
    /** @var CMSAPIBundle */
    private $bundle;

    /**
     * Should create proper instance.
     */
    public function testInstance(): void
    {
        $this->assertInstanceOf(CMSAPIBundle::class, $this->bundle);
    }

    /**
     * Setup tests.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->bundle = new CMSAPIBundle();
    }
}
