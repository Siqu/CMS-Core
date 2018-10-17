<?php

namespace Siqu\CMS\Core\Tests\DependencyInjection;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Siqu\CMS\Core\DependencyInjection\CMSCoreExtension;
use Siqu\CMS\Core\Doctrine\Listener\CMSUserListener;
use Siqu\CMS\Core\Util\PasswordUpdater;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class CMSCoreExtensionTest
 * @package Siqu\CMS\Core\Tests\DependencyInjection
 */
class CMSCoreExtensionTest extends TestCase
{
    /** @var CMSCoreExtension */
    private $extension;

    /** @var ContainerBuilder|MockObject */
    private $container;

    /**
     * Should create instance.
     */
    public function testConstruct(): void
    {
        $this->assertInstanceOf(CMSCoreExtension::class, $this->extension);
    }

    /**
     * Should load service definition.
     */
    public function testLoad(): void
    {
        $this->extension->load([
            'siqu_cms_core' => []
        ], $this->container);

        $definition = $this->container->getDefinition('siqu.cms_core.doctrine.listener.cms_user_listener');
        $this->assertEquals(CMSUserListener::class, $definition->getClass());
        $this->assertFalse($definition->isPublic());
        $tags = $definition->getTags();
        $this->assertArrayHasKey('doctrine.event_listener', $tags);
        $arguments = $definition->getArguments();
        /** @var Reference $ref */
        $ref = $arguments[0];
        $this->assertEquals('siqu.cms_core.util.password_updater', $ref);

        $definition = $this->container->getDefinition('siqu.cms_core.util.password_updater');
        $this->assertEquals(PasswordUpdater::class, $definition->getClass());
        $this->assertFalse($definition->isPublic());
        $arguments = $definition->getArguments();
        /** @var Reference $ref */
        $ref = $arguments[0];
        $this->assertEquals('security.encoder_factory', $ref);
    }

    /**
     * Setup tests.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->container = new ContainerBuilder();

        $this->extension = new CMSCoreExtension();
    }
}
