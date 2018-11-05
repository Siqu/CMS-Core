<?php

namespace Siqu\CMS\Core\Tests\DependencyInjection;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Siqu\CMS\Core\DependencyInjection\CMSCoreExtension;
use Siqu\CMS\Core\Doctrine\Listener\BlameableListener;
use Siqu\CMS\Core\Doctrine\Listener\CMSUserListener;
use Siqu\CMS\Core\Doctrine\Listener\IdentifiableListener;
use Siqu\CMS\Core\Doctrine\Listener\TimestampableListener;
use Siqu\CMS\Core\Util\PasswordUpdater;
use Siqu\CMS\Core\Util\UuidGenerator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class CMSCoreExtensionTest
 * @package Siqu\CMS\Core\Tests\DependencyInjection
 */
class CMSCoreExtensionTest extends TestCase
{
    /** @var ContainerBuilder|MockObject */
    private $container;
    /** @var CMSCoreExtension */
    private $extension;

    /**
     * Should create instance.
     */
    public function testConstruct(): void
    {
        $this->assertInstanceOf(
            CMSCoreExtension::class,
            $this->extension
        );
    }

    /**
     * Should load service definition.
     */
    public function testLoad(): void
    {
        $this->extension->load(
            [
                'siqu_cms_core' => []
            ],
            $this->container
        );

        $definition = $this->container->getDefinition('siqu.cms_core.doctrine.listener.cms_user');
        $this->assertEquals(
            CMSUserListener::class,
            $definition->getClass()
        );
        $this->assertFalse($definition->isPublic());
        $tags = $definition->getTags();
        $this->assertArrayHasKey(
            'doctrine.event_subscriber',
            $tags
        );
        $arguments = $definition->getArguments();
        /** @var Reference $ref */
        $ref = $arguments[0];
        $this->assertEquals(
            'siqu.cms_core.util.password_updater',
            $ref
        );
        /** @var Reference $ref */
        $ref = $arguments[1];
        $this->assertEquals(
            'siqu.cms_core.util.uuid_generator',
            $ref
        );

        $definition = $this->container->getDefinition('siqu.cms_core.doctrine.listener.blameable');
        $this->assertEquals(
            BlameableListener::class,
            $definition->getClass()
        );
        $this->assertFalse($definition->isPublic());
        $tags = $definition->getTags();
        $this->assertArrayHasKey(
            'doctrine.event_subscriber',
            $tags
        );
        $arguments = $definition->getArguments();
        /** @var Reference $ref */
        $ref = $arguments[0];
        $this->assertEquals(
            'security.token_storage',
            $ref
        );

        $definition = $this->container->getDefinition('siqu.cms_core.doctrine.listener.timestampable');
        $this->assertEquals(
            TimestampableListener::class,
            $definition->getClass()
        );
        $this->assertFalse($definition->isPublic());
        $tags = $definition->getTags();
        $this->assertArrayHasKey(
            'doctrine.event_subscriber',
            $tags
        );

        $definition = $this->container->getDefinition('siqu.cms_core.doctrine.listener.identifiable');
        $this->assertEquals(
            IdentifiableListener::class,
            $definition->getClass()
        );
        $this->assertFalse($definition->isPublic());
        $tags = $definition->getTags();
        $this->assertArrayHasKey(
            'doctrine.event_subscriber',
            $tags
        );

        $definition = $this->container->getDefinition('siqu.cms_core.util.password_updater');
        $this->assertEquals(
            PasswordUpdater::class,
            $definition->getClass()
        );
        $this->assertFalse($definition->isPublic());
        $arguments = $definition->getArguments();
        /** @var Reference $ref */
        $ref = $arguments[0];
        $this->assertEquals(
            'security.encoder_factory',
            $ref
        );

        $definition = $this->container->getDefinition('siqu.cms_core.util.uuid_generator');
        $this->assertEquals(
            UuidGenerator::class,
            $definition->getClass()
        );
        $this->assertFalse($definition->isPublic());
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
