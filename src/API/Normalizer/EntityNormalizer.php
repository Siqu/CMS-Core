<?php

namespace Siqu\CMS\API\Normalizer;

use Doctrine\ORM\EntityManagerInterface;
use Siqu\CMS\Core\Entity\Traits\IdentifiableTrait;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\PropertyInfo\PropertyTypeExtractorInterface;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactoryInterface;
use Symfony\Component\Serializer\NameConverter\NameConverterInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
 * Class EntityNormalizer
 * @package Siqu\CMS\API\Normalizer
 */
class EntityNormalizer extends ObjectNormalizer
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * EntityNormalizer constructor.
     * @param EntityManagerInterface $entityManager
     * @param ClassMetadataFactoryInterface|null $classMetadataFactory
     * @param NameConverterInterface|null $nameConverter
     * @param PropertyAccessorInterface|null $propertyAccessor
     * @param PropertyTypeExtractorInterface|null $propertyTypeExtractor
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        ClassMetadataFactoryInterface $classMetadataFactory = null,
        NameConverterInterface $nameConverter = null,
        PropertyAccessorInterface $propertyAccessor = null,
        PropertyTypeExtractorInterface $propertyTypeExtractor = null
    )
    {
        parent::__construct($classMetadataFactory, $nameConverter, $propertyAccessor, $propertyTypeExtractor);

        $this->entityManager = $entityManager;

        $this->setCircularReferenceHandler(function ($object) {
            /** @var $object IdentifiableTrait */
            return [
                'uuid' => $object->getUuid()
            ];
        });
    }

    /**
     * @param $data
     * @param $class
     * @param null $format
     * @param array $context
     * @return null|object
     */
    public function denormalize($data, $class, $format = null, array $context = array())
    {
        return $this->entityManager->find($class, $data);
    }

    /**
     * @param $data
     * @param $type
     * @param null $format
     * @return bool|mixed
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return
            (strpos($type, 'Siqu\\CMS\\Core\\Entity') === 0) &&
            (is_numeric($data) || is_string($data) || (isset($data['id'])));
    }

    public function supportsNormalization($data, $format = null)
    {
        if (!is_object($data)) {
            return false;
        }
        $class = new \ReflectionClass($data);
        return $class->getNamespaceName() === 'Siqu\\CMS\\Core\\Entity';
    }
}