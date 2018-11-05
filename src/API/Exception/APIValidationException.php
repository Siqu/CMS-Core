<?php

namespace Siqu\CMS\API\Exception;

use Symfony\Component\Validator\ConstraintViolationListInterface;
use Throwable;

/**
 * Class APIValidationException
 * @package Siqu\CMS\API\Exception
 */
class APIValidationException extends \Exception
{
    /** @var ConstraintViolationListInterface */
    private $violations;

    /**
     * APIValidationException constructor.
     * @param ConstraintViolationListInterface $violations
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(
        ConstraintViolationListInterface $violations,
        string $message = "",
        int $code = 0,
        Throwable $previous = null
    ) {
        parent::__construct(
            $message,
            $code,
            $previous
        );

        $this->violations = $violations;
    }

    /**
     * Retrieve the violations.
     *
     * @return ConstraintViolationListInterface
     */
    public function getViolations(): ConstraintViolationListInterface
    {
        return $this->violations;
    }
}
