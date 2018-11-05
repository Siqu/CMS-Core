<?php

namespace Siqu\CMS\Core\Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Siqu\CMS\Core\DependencyInjection\Configuration;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * Class ConfigurationTest
 * @package Siqu\CMS\Core\Tests\DependencyInjection
 */
class ConfigurationTest extends TestCase
{
    /** @var Configuration */
    private $configuration;

    /**
     * Should create instance
     */
    public function testConstruct(): void
    {
        $this->assertInstanceOf(
            Configuration::class,
            $this->configuration
        );
    }

    /**
     * Should return tree builder for bundle.
     */
    public function testGetConfigTreeBuilder(): void
    {
        $builder = $this->configuration->getConfigTreeBuilder();

        $this->assertInstanceOf(
            TreeBuilder::class,
            $builder
        );

        $tree = $builder->buildTree();

        $this->assertEquals(
            'siqu_cms_core',
            $tree->getName()
        );
    }

    /**
     * Setup tests.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->configuration = new Configuration();
    }
}
