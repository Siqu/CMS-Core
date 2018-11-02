<?php

namespace Siqu\CMS\API\Tests\Normalizer;

use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Siqu\CMS\API\Normalizer\CircularReferenceHandlerInterface;
use Siqu\CMS\API\Normalizer\EntityNormalizer;
use Siqu\CMS\Core\Entity\Page;

/**
 * Class EntityNormalizerTest
 * @package Siqu\CMS\API\Tests\Normalizer
 */
class EntityNormalizerTest extends TestCase
{
    /** @var CircularReferenceHandlerInterface|MockObject */
    private $circularReferenceHandler;
    /** @var EntityManagerInterface|MockObject */
    private $entityManager;
    /** @var EntityNormalizer */
    private $normalizer;

    /**
     * Should create correct instance
     */
    public function testConstruct(): void
    {
        $this->assertInstanceOf(EntityNormalizer::class, $this->normalizer);
    }

    /**
     * Should call entitymanager.
     */
    public function testDenormalize(): void
    {
        $data = [
            'id' => 1
        ];

        $this->entityManager
            ->expects($this->once())
            ->method('find')
            ->with(\stdClass::class, $data);

        $this->normalizer->denormalize($data, \stdClass::class);
    }

    /**
     * Should return true for array with id key data.
     */
    public function testSupportsDenormalizationArray(): void
    {
        $this->assertTrue($this->normalizer->supportsDenormalization([
            'id' => 1
        ], Page::class));
    }

    /**
     * Should return true for integer data.
     */
    public function testSupportsDenormalizationInteger(): void
    {
        $this->assertTrue($this->normalizer->supportsDenormalization(1, Page::class));
    }

    /**
     * Should return true for string data.
     */
    public function testSupportsDenormalizationString(): void
    {
        $this->assertTrue($this->normalizer->supportsDenormalization('1', Page::class));
    }

    /**
     * Should return true for correct object.
     *
     * @throws \ReflectionException
     */
    public function testSupportsNormalizationCorrectObject(): void
    {
        $data = new Page();
        $this->assertTrue($this->normalizer->supportsNormalization($data));
    }

    /**
     * Should return false for incorrect object.
     *
     * @throws \ReflectionException
     */
    public function testSupportsNormalizationInvalidObject(): void
    {
        $this->assertFalse($this->normalizer->supportsNormalization(null));
    }

    /**
     * Setup tests.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->entityManager = $this->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->circularReferenceHandler = $this->getMockBuilder(CircularReferenceHandlerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->normalizer = new EntityNormalizer($this->circularReferenceHandler, $this->entityManager);
    }
}
