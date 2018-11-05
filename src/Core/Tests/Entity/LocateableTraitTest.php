<?php

namespace Siqu\CMS\Core\Tests\Entity;

use PHPUnit\Framework\TestCase;
use Siqu\CMS\Core\Entity\LocateableTrait;

/**
 * Class LocateableTraitTest
 * @package Siqu\CMS\Core\Tests\Entity
 */
class LocateableTraitTest extends TestCase
{
    /** @var LocateableTrait */
    private $object;

    /**
     * Should set and return latitude.
     *
     * @LocateableTrait::setLat
     * @LocateableTrait::getLat
     */
    public function testSetGetLat(): void
    {
        $this->assertNull($this->object->getLat());
        $this->object->setLat('99.99999999');
        $this->assertEquals(
            '99.99999999',
            $this->object->getLat()
        );
    }

    /**
     * Should set and return longitude.
     *
     * @LocateableTrait::setLng
     * @LocateableTrait::getLng
     */
    public function testSetGetLng(): void
    {
        $this->assertNull($this->object->getLng());
        $this->object->setLng('99.99999999');
        $this->assertEquals(
            '99.99999999',
            $this->object->getLng()
        );
    }

    /**
     * Should set and return location.
     * @LocateableTrait::setLocation
     * @LocateableTrait::getLocation
     */
    public function testSetGetLocation(): void
    {
        $this->assertNull($this->object->getLocation());
        $this->object->setLocation('location');
        $this->assertEquals(
            'location',
            $this->object->getLocation()
        );
    }

    /**
     * Setup tests.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->object = $this->getMockForTrait(LocateableTrait::class);
    }
}
