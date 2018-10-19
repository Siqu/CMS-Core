<?php

namespace Siqu\CMS\API\Security;

/**
 * Class Credentials
 * @package Siqu\CMS\API\Security
 */
class Credentials
{
    /** @var string */
    private $password;
    /** @var string */
    private $username;

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
     * Retrieve password.
     *
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
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
}