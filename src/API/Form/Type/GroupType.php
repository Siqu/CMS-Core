<?php

namespace Siqu\CMS\API\Form\Type;

use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class GroupType
 * @package Siqu\CMS\API\Form\Type
 */
class GroupType extends AbstractAPIType
{
    /**
     * Build for for Group
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('name')
            ->add('roles', CollectionType::class, [
                'allow_add' => true,
                'allow_delete' => true
            ])
            ->add('uuid', TextType::class, [
                'mapped' => false
            ]);
    }
}