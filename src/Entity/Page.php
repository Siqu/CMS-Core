<?php

namespace Siqu\CMSCore\Entity;

use Doctrine\ORM\Mapping as ORM;
use Siqu\CMSCore\Entity\Traits\IdentifiableTrait;
use Siqu\CMSCore\Entity\Traits\NameableTrait;

/**
 * Class Page
 * @package Siqu\CMSCore\Entity
 * @ORM\Entity()
 * @ORM\Table(name="cms_page")
 */
class Page
{
    use IdentifiableTrait;
    use NameableTrait;
}