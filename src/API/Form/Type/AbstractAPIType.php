<?php

namespace Siqu\CMS\API\Form\Type;

use Siqu\CMS\API\Validation\ValidationGroupResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AbstractAPIType
 * @package Siqu\CMS\API\Form\Type
 */
class AbstractAPIType extends AbstractType
{
    /** @var ValidationGroupResolver */
    private $validationGroupResolver;

    /**
     * CMSUserType constructor.
     * @param ValidationGroupResolver $validationGroupResolver
     */
    public function __construct(
        ValidationGroupResolver $validationGroupResolver
    )
    {
        $this->validationGroupResolver = $validationGroupResolver;
    }

    /**
     * Add validation group resolver.
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'validation_groups' => $this->validationGroupResolver
        ]);
    }
}