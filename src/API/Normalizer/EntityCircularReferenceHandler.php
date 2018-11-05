<?php

namespace Siqu\CMS\API\Normalizer;

use Siqu\CMS\Core\Entity\IdentifiableTrait;

/**
 * Class EntityCircularReferenceHandler
 * @package Siqu\CMS\API\Normalizer
 */
class EntityCircularReferenceHandler implements CircularReferenceHandlerInterface
{
    /**
     * Handle a identifiable trait object for circular reference.
     *
     * @param mixed $data
     * @return mixed
     * @throws \ReflectionException
     */
    public function handle($data)
    {
        if (!is_object($data)) {
            return $data;
        }

        $reflection = new \ReflectionClass($data);
        $traits = $reflection->getTraitNames();

        if (in_array(IdentifiableTrait::class, $traits)) {
            /** @var IdentifiableTrait $data
             */
            return [
                'uuid' => $data->getUuid()
            ];
        }

        return $data;
    }
}
