<?php

namespace Siqu\CMS\Core\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Class CMSCoreExtension
 * @package Siqu\CMS\Core\DependencyInjection
 */
class CMSCoreExtension extends Extension
{

    /**
     * Loads a specific configuration.
     *
     * @param array $configs
     * @param ContainerBuilder $container
     * @throws \Exception When service yaml files could not be loaded
     */
    public function load(
        array $configs,
        ContainerBuilder $container
    ) {
        $configuration = $this->getConfiguration(
            $configs,
            $container
        );
        $this->processConfiguration(
            $configuration,
            $configs
        );

        $this->loadServices($container);
    }

    /**
     * Load service definition files.
     *
     * @param ContainerBuilder $container
     * @throws \Exception
     */
    private function loadServices(ContainerBuilder $container)
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config')
        );
        $loader->load('services.yaml');
    }
}
