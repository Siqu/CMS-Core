<?php

namespace Siqu\CMS\API\Tests\Exception;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Siqu\CMS\API\Exception\APIValidationException;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Class APIValidationExceptionTest
 * @package Siqu\CMS\API\Tests\Exception
 */
class APIValidationExceptionTest extends TestCase
{
    /** @var ConstraintViolationListInterface|MockObject */
    private $violations;

    /** @var APIValidationException */
    private $exception;

    /**
     * Should create proper instance
     */
    public function testConstruct(): void
    {
        $this->assertInstanceOf(APIValidationException::class, $this->exception);
    }

    /**
     * Should return violations from constructor.
     */
    public function testGetViolations(): void
    {
        $this->assertEquals($this->violations, $this->exception->getViolations());
    }

    /**
     * Setup tests.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->violations = $this->getMockBuilder(ConstraintViolationListInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->exception = new APIValidationException($this->violations);
    }
}
