<?php

namespace Siqu\CMS\Core\Util;

/**
 * Class TokenGenerator
 * @package Siqu\CMS\Core\Util
 */
class TokenGenerator
{
    /**
     * Generate a random token.
     *
     * @return string
     * @throws \Exception
     */
    public function generateToken(): string {
        return rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
    }
}