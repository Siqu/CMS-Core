<?php

namespace Siqu\CMS\Core\Tests\Util;

use PHPUnit\Framework\TestCase;
use Siqu\CMS\Core\Util\Urlizer;

/**
 * Class UrlizerTest
 * @package Siqu\CMS\Core\Tests\Util
 */
class UrlizerTest extends TestCase
{
    /** @var Urlizer */
    private $object;

    /**
     * Should generate slug.
     */
    public function testGenerateSlug()
    {
        $this->assertEquals('title', $this->object->generateSlug('Title'));
    }

    /**
     * Setup tests.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->object = new Urlizer();
    }
}
