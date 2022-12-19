<?php

namespace Lamoda\AtolClientBundle\DependencyInjection;

use Lamoda\AtolClient\V3\AtolApi as AtolApiV3;
use Lamoda\AtolClient\V4\AtolApi as AtolApiV4;
use Lamoda\AtolClientBundle\AtolClientBundle;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;

final class AtolClientExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $this->loadConfig($container);
        $this->createAtolClients($config, $container);
    }

    private function loadConfig(ContainerBuilder $container): void
    {
        $locator = new FileLocator(__DIR__ . '/../Resources/config');
        $loader = new YamlFileLoader($container, $locator);
        $loader->load('services.yml');
    }

    private function createAtolClients(array $config, ContainerBuilder $container): void
    {
        foreach ($config['clients'] as $name => $clientConfig) {
            $this->createAtolClient($name, $clientConfig, $container);
        }

        if ($container->hasDefinition('atol_client.v3.default')) {
            $container->setAlias('atol_client.v3', 'atol_client.v3.default');
            $container->setAlias(AtolApiV3::class, 'atol_client.v3.default');
        }

        if ($container->hasDefinition('atol_client.v4.default')) {
            $container->setAlias('atol_client.v4', 'atol_client.v4.default');
            $container->setAlias(AtolApiV4::class, 'atol_client.v4.default');
        }

        if ($container->hasDefinition('atol_client.v5.default')) {
            $container->setAlias('atol_client.v5', 'atol_client.v5.default');
            $container->setAlias(AtolApiV5::class, 'atol_client.v5.default');
        }
    }

    private function createAtolClient(string $name, array $clientConfig, ContainerBuilder $container): void
    {
        switch ($clientConfig['version']) {
            case AtolClientBundle::API_CLIENT_VERSION_3:
                $this->createAtolClientV3($name, $clientConfig, $container);
                break;
            case AtolClientBundle::API_CLIENT_VERSION_4:
                $this->createAtolClientV4($name, $clientConfig, $container);
                break;
            case AtolClientBundle::API_CLIENT_VERSION_5:
                $this->createAtolClientV5($name, $clientConfig, $container);
                break;
            default:
                throw new InvalidArgumentException('Wrong client version: ' . $clientConfig['version']);
        }
    }

    private function createAtolClientV3(string $name, array $clientConfig, ContainerBuilder $container): void
    {
        $definition = new Definition(AtolApiV3::class, [
            new Reference('atol_client.object_converter'),
            new Reference($clientConfig['guzzle_client']),
            $clientConfig['guzzle_client_options'],
            $clientConfig['base_url'],
            $clientConfig['cash_register_group_code'],
        ]);
        $definition->setPublic(false);

        $id = 'atol_client.v3.' . $name;

        $container->setDefinition($id, $definition);
    }

    private function createAtolClientV4(string $name, array $clientConfig, ContainerBuilder $container): void
    {
        $definition = new Definition(AtolApiV4::class, [
            new Reference('atol_client.object_converter'),
            new Reference($clientConfig['guzzle_client']),
            $clientConfig['guzzle_client_options'],
            $clientConfig['base_url'],
        ]);
        $definition->setPublic(false);

        $id = 'atol_client.v4.' . $name;

        $container->setDefinition($id, $definition);
    }

    private function createAtolClientV5(string $name, array $clientConfig, ContainerBuilder $container): void
    {
        $definition = new Definition(AtolApiV5::class, [
            new Reference('atol_client.object_converter'),
            new Reference($clientConfig['guzzle_client']),
            $clientConfig['guzzle_client_options'],
            $clientConfig['base_url'],
        ]);
        $definition->setPublic(false);

        $id = 'atol_client.v5.' . $name;

        $container->setDefinition($id, $definition);
    }
}
