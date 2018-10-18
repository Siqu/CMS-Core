<?php

namespace Siqu\CMS\API\Security;

/**
 * Class Credentials
 * @package Siqu\CMS\API\Security
 */
class Credentials
{
    /** @var string */
    private $username;

    /** @var string */
    private $password;

    /**
     * Credentials constructor.
     * @param string $username
     * @param string $password
     */
    public function __construct(
        string $username,
        string $password
    )
    {
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Retrieve username.
     *
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * Retrieve password.
     *
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }
}