<?php

namespace Siqu\CMS\API\Deserializer;

use Siqu\CMS\API\Exception\APIValidationException;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class RequestDeserializer
 * @package Siqu\CMS\API\Deserializer
 */
class RequestDeserializer
{
    /** @var RequestStack */
    private $requestStack;
    /** @var SerializerInterface */
    private $serializer;
    /** @var ValidatorInterface */
    private $validator;

    /**
     * RequestDeserializer constructor.
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     * @param RequestStack $requestStack
     */
    public function __construct(
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        RequestStack $requestStack
    ) {
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->requestStack = $requestStack;
    }

    /**
     * Deserialize request data into a entity.
     *
     * @param string $entity
     * @param string $validationGroup
     * @param null $entry
     * @return object
     * @throws APIValidationException
     */
    public function deserializerRequest(
        string $entity,
        string $validationGroup = 'new',
        $entry = null
    ): object {
        if (!$entry) {
            $entry = new $entity();
        }
        $request = $this->requestStack->getMasterRequest();

        $entry = $this->serializer->deserialize(
            $request->getContent(),
            $entity,
            $request->getFormat($request->getRequestFormat()),
            [
                'object_to_populate' => $entry
            ]
        );

        $errors = $this->validator->validate(
            $entry,
            null,
            $validationGroup
        );

        if (count($errors) > 0) {
            throw new APIValidationException($errors);
        }

        return $entry;
    }
}
