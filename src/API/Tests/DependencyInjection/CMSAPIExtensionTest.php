<?php

namespace Siqu\CMS\API\Tests\DependencyInjection;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Siqu\CMS\API\DependencyInjection\CMSAPIExtension;
use Siqu\CMS\API\EventListener\AcceptLanguageListener;
use Siqu\CMS\API\EventListener\AcceptListener;
use Siqu\CMS\API\Normalizer\EntityCircularReferenceHandler;
use Siqu\CMS\API\Normalizer\EntityNormalizer;
use Siqu\CMS\API\Security\ApiAuthenticator;
use Siqu\CMS\API\Security\ApiUserProvider;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class CMSAPIExtensionTest
 * @package Siqu\CMS\API\Tests\DependencyInjection
 */
class CMSAPIExtensionTest extends TestCase
{
    /** @var ContainerBuilder|MockObject */
    private $container;
    /** @var CMSAPIExtension */
    private $extension;

    /**
     * Should create instance.
     */
    public function testConstruct(): void
    {
        $this->assertInstanceOf(CMSAPIExtension::class, $this->extension);
    }

    /**
     * Should load service definition.
     */
    public function testLoad(): void
    {
        $this->extension->load([
            'siqu_cms_api' => []
        ], $this->container);

        $definition = $this->container->getDefinition('siqu.cms_api.event_listener.request.accept_language');
        $this->assertEquals(AcceptLanguageListener::class, $definition->getClass());
        $this->assertFalse($definition->isPublic());
        $tags = $definition->getTags();
        $this->assertArrayHasKey('kernel.event_listener', $tags);
        $tag = $tags['kernel.event_listener'];
        $this->assertEquals(KernelEvents::REQUEST, $tag[0]['event']);
        $this->assertEquals(15, $tag[0]['priority']);

        $definition = $this->container->getDefinition('siqu.cms_api.event_listener.request.accept');
        $this->assertEquals(AcceptListener::class, $definition->getClass());
        $this->assertFalse($definition->isPublic());
        $tags = $definition->getTags();
        $this->assertArrayHasKey('kernel.event_listener', $tags);
        $tag = $tags['kernel.event_listener'];
        $this->assertEquals(KernelEvents::REQUEST, $tag[0]['event']);
        $this->assertEquals(17, $tag[0]['priority']);

        $definition = $this->container->getDefinition('siqu.cms_api.normalizer.object.entity');
        $this->assertEquals(EntityNormalizer::class, $definition->getClass());
        $this->assertFalse($definition->isPublic());
        $tags = $definition->getTags();
        $this->assertArrayHasKey('serializer.normalizer', $tags);
        $arguments = $definition->getArguments();
        /** @var Reference $ref */
        $ref = $arguments[0];
        $this->assertEquals('siqu.cms_api.normalizer.circular_reference_handler.identifiable_trait', $ref);
        $arguments = $definition->getArguments();
        /** @var Reference $ref */
        $ref = $arguments[1];
        $this->assertEquals('doctrine.orm.entity_manager', $ref);
        /** @var Reference $ref */
        $ref = $arguments[2];
        $this->assertEquals('serializer.mapping.class_metadata_factory', $ref);
        /** @var Reference $ref */
        $ref = $arguments[3];
        $this->assertNull($ref);
        /** @var Reference $ref */
        $ref = $arguments[4];
        $this->assertEquals('serializer.property_accessor', $ref);
        /** @var Reference $ref */
        $ref = $arguments[5];
        $this->assertEquals('property_info', $ref);

        $definition = $this->container->getDefinition('siqu.cms_api.normalizer.circular_reference_handler.identifiable_trait');
        $this->assertEquals(EntityCircularReferenceHandler::class, $definition->getClass());
        $this->assertFalse($definition->isPublic());

        $definition = $this->container->getDefinition('siqu.cms_api.security.provider.user');
        $this->assertEquals(ApiUserProvider::class, $definition->getClass());
        $this->assertFalse($definition->isPublic());
        $arguments = $definition->getArguments();
        /** @var Reference $ref */
        $ref = $arguments[0];
        $this->assertEquals('doctrine.orm.entity_manager', $ref);
        /** @var Reference $ref */
        $ref = $arguments[1];
        $this->assertEquals('security.password_encoder', $ref);

        $definition = $this->container->getDefinition('siqu.cms_api.security.authenticator');
        $this->assertEquals(ApiAuthenticator::class, $definition->getClass());
        $this->assertFalse($definition->isPublic());

    }

    /**
     * Setup tests.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->container = new ContainerBuilder();

        $this->extension = new CMSAPIExtension();
    }
}
