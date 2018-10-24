<?php

namespace Siqu\CMS\Core\Util;

use Behat\Transliterator\Transliterator;

/**
 * Class Urlizer
 * @package Siqu\CMS\Core\Util
 */
class Urlizer extends Transliterator
{
    /**
     * Generate a slug.
     *
     * @param string $slug
     * @return string
     */
    public function generateSlug(string $slug): string
    {
        return self::urlize($slug);
    }
}