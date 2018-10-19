<?php

namespace Siqu\CMS\API\Validation;

use Siqu\CMS\Core\Entity\Traits\IdentifiableTrait;
use Symfony\Component\Form\FormInterface;

/**
 * Class ValidationGroupResolver
 * @package Siqu\CMS\API\Validation
 */
class ValidationGroupResolver
{
    /**
     * Retrieve validation groups depending on the form submission
     * @param FormInterface $form
     * @return array
     */
    public function __invoke(FormInterface $form)
    {
        /** @var IdentifiableTrait $data */
        $data = $form->getData();

        if (!$data || $data->getUuid() === null) {
            return ['new'];
        }

        return ['update'];
    }
}