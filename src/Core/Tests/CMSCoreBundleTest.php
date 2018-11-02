<?php

namespace Siqu\CMS\Core\Tests;

use PHPUnit\Framework\TestCase;
use Siqu\CMS\Core\CMSCoreBundle;

/**
 * Class CMSCoreBundleTest
 * @package Siqu\CMS\Core\Tests
 */
class CMSCoreBundleTest extends TestCase
{
    /** @var CMSCoreBundle */
    private $bundle;

    /**
     * Should create proper instance.
     */
    public function testCreateInstance(): void
    {
        $this->assertInstanceOf(CMSCoreBundle::class, $this->bundle);
    }

    /**
     * Setup tests.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->bundle = new CMSCoreBundle();
    }
}
