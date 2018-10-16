<?php

namespace Siqu\CMS\Core\Entity;

use Doctrine\ORM\Mapping as ORM;
use Siqu\CMS\Core\Entity\Traits\IdentifiableTrait;
use Siqu\CMS\Core\Entity\Traits\NameableTrait;

/**
 * Class Page
 * @package Siqu\CMS\Core\Entity
 * @ORM\Entity()
 * @ORM\Table(name="cms_page")
 */
class Page
{
    use IdentifiableTrait;
    use NameableTrait;
}