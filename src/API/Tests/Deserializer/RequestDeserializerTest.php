<?php

namespace Siqu\CMS\API\Tests\Deserializer;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Siqu\CMS\API\Deserializer\RequestDeserializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class RequestDeserializerTest
 * @package Siqu\CMS\API\Tests\Deserializer
 */
class RequestDeserializerTest extends TestCase
{
    /** @var RequestDeserializer */
    private $deserializer;
    /** @var RequestStack|MockObject */
    private $requestStack;
    /** @var SerializerInterface|MockObject */
    private $serializer;
    /** @var ValidatorInterface|MockObject */
    private $validator;

    /**
     * Should return proper object.
     */
    public function testDeserializerRequest(): void
    {
        $request = new Request(
            [],
            [],
            [],
            [],
            [],
            [],
            'content'
        );
        $request->setRequestFormat('application/json');

        $this->requestStack
            ->expects($this->once())
            ->method('getMasterRequest')
            ->willReturn($request);

        $data = new \stdClass();

        $this->serializer
            ->expects($this->once())
            ->method('deserialize')
            ->with(
                'content',
                \stdClass::class,
                'json',
                [
                    'object_to_populate' => $data
                ]
            )
            ->willReturn($data);

        $this->validator
            ->expects($this->once())
            ->method('validate')
            ->with(
                $data,
                null,
                'new'
            )
            ->willReturn(new ConstraintViolationList());

        $object = $this->deserializer->deserializerRequest(\stdClass::class);

        $this->assertEquals(
            $data,
            $object
        );
    }

    /**
     * Should return proper object.
     */
    public function testDeserializerRequestWithDifferentValidation(): void
    {
        $request = new Request(
            [],
            [],
            [],
            [],
            [],
            [],
            'content'
        );
        $request->setRequestFormat('application/json');

        $this->requestStack
            ->expects($this->once())
            ->method('getMasterRequest')
            ->willReturn($request);

        $data = new \stdClass();

        $this->serializer
            ->expects($this->once())
            ->method('deserialize')
            ->with(
                'content',
                \stdClass::class,
                'json',
                [
                    'object_to_populate' => $data
                ]
            )
            ->willReturn($data);

        $this->validator
            ->expects($this->once())
            ->method('validate')
            ->with(
                $data,
                null,
                'test'
            )
            ->willReturn(new ConstraintViolationList());

        $object = $this->deserializer->deserializerRequest(
            \stdClass::class,
            'test'
        );

        $this->assertEquals(
            $data,
            $object
        );
    }

    /**
     * Should throw exception
     *
     * @expectedException Siqu\CMS\API\Exception\APIValidationException
     */
    public function testDeserializerRequestWithErrors(): void
    {
        $request = new Request(
            [],
            [],
            [],
            [],
            [],
            [],
            'content'
        );
        $request->setRequestFormat('application/json');

        $this->requestStack
            ->expects($this->once())
            ->method('getMasterRequest')
            ->willReturn($request);

        $data = new \stdClass();

        $this->serializer
            ->expects($this->once())
            ->method('deserialize')
            ->with(
                'content',
                \stdClass::class,
                'json',
                [
                    'object_to_populate' => $data
                ]
            )
            ->willReturn($data);

        $this->validator
            ->expects($this->once())
            ->method('validate')
            ->with(
                $data,
                null,
                'new'
            )
            ->willReturn(
                new ConstraintViolationList(
                    [
                        new ConstraintViolation(
                            'message',
                            'template',
                            [],
                            null,
                            null,
                            null
                        )
                    ]
                )
            );

        $object = $this->deserializer->deserializerRequest(\stdClass::class);
    }

    /**
     * Should return proper object.
     */
    public function testDeserializerRequestWithExistingObject(): void
    {
        $request = new Request(
            [],
            [],
            [],
            [],
            [],
            [],
            'content'
        );
        $request->setRequestFormat('application/json');

        $this->requestStack
            ->expects($this->once())
            ->method('getMasterRequest')
            ->willReturn($request);

        $data = new \stdClass();

        $this->serializer
            ->expects($this->once())
            ->method('deserialize')
            ->with(
                'content',
                \stdClass::class,
                'json',
                [
                    'object_to_populate' => $data
                ]
            )
            ->willReturn($data);

        $this->validator
            ->expects($this->once())
            ->method('validate')
            ->with(
                $data,
                null,
                'new'
            )
            ->willReturn(new ConstraintViolationList());

        $object = $this->deserializer->deserializerRequest(
            \stdClass::class,
            'new',
            $data
        );

        $this->assertEquals(
            $data,
            $object
        );
    }

    /**
     * Should create proper instance.
     */
    public function testInstance(): void
    {
        $this->assertInstanceOf(
            RequestDeserializer::class,
            $this->deserializer
        );
    }

    /**
     * Setup tests.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->serializer = $this->getMockBuilder(SerializerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->validator = $this->getMockBuilder(ValidatorInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->requestStack = $this->getMockBuilder(RequestStack::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->deserializer = new RequestDeserializer(
            $this->serializer,
            $this->validator,
            $this->requestStack
        );
    }
}
