<?php

namespace DependencyInjection;

use PHPUnit\Framework\TestCase;
use Siqu\CMSCore\DependencyInjection\Configuration;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * Class ConfigurationTest
 * @package DependencyInjection
 */
class ConfigurationTest extends TestCase
{
    /** @var Configuration */
    private $configuration;

    /**
     * Should create instance
     */
    public function testConstruct() {
        $this->assertInstanceOf(Configuration::class, $this->configuration);
    }

    /**
     * Should return tree builder for bundle.
     */
    public function testGetConfigTreeBuilder()
    {
        $builder = $this->configuration->getConfigTreeBuilder();

        $this->assertInstanceOf(TreeBuilder::class, $builder);

        $tree = $builder->buildTree();

        $this->assertEquals('siqu_cms_core', $tree->getName());
    }

    /**
     * Setup tests.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->configuration = new Configuration();
    }
}
