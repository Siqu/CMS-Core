<?php

namespace Siqu\CMS\API\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class CMSUserType
 * @package Siqu\CMS\API\Form
 */
class CMSUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('username')
            ->add('plainPassword')
            ->add('email');
    }
}