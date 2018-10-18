<?php

namespace Siqu\CMS\API\Form\Type;

use Siqu\CMS\API\Validation\ValidationGroupResolver;
use Siqu\CMS\Core\Entity\Group;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CMSUserType
 * @package Siqu\CMS\API\Form\Type
 */
class CMSUserType extends AbstractAPIType
{
    /**
     * Build form for CMSUser.
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('username', TextType::class)
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'form.cms_user.password.match'
            ])
            ->add('email', EmailType::class)
            ->add('enabled', CheckboxType::class)
            ->add('confirmationToken', TextType::class)
            ->add('accountNonExpired', CheckboxType::class)
            ->add('credentialsNonExpired', CheckboxType::class)
            ->add('credentialsNonExpired', CheckboxType::class)
            ->add('accountNonLocked', CheckboxType::class)
            ->add('groups', CollectionType::class, [
                'entry_type' => EntityType::class,
                'entry_options' => [
                    'class' => Group::class
                ]
            ])
            ->add('roles', CollectionType::class, [
                'allow_add' => true,
                'allow_delete' => true,
            ])
            ->add('lastLogin', TextType::class, [
                'mapped' => false
            ])
            ->add('uuid', TextType::class, [
                'mapped' => false
            ]);
    }
}