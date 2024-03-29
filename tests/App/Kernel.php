<?php

namespace Lamoda\AtolClientBundle\Tests\App;

use JMS\SerializerBundle\JMSSerializerBundle;
use Lamoda\AtolClientBundle\AtolClientBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel as SymfonyKernel;

final class Kernel extends SymfonyKernel
{
    public function registerBundles()
    {
        return [
            new FrameworkBundle(),
            new JMSSerializerBundle(),
            new AtolClientBundle(),
        ];
    }

    /**
     * Loads the container configuration.
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getProjectDir() . '/tests/App/config/config.yaml');
        $clientConfig = $this->getProjectDir() . '/tests/App/config/config_' . $this->environment . '.yaml';
        if (!file_exists($clientConfig)) {
            $clientConfig = $this->getProjectDir() . '/tests/App/config/config_single_client.yaml';
        }
        $loader->load($clientConfig);
    }

    public function getCacheDir()
    {
        return $this->getProjectDir() . '/build/var/cache/' . $this->environment;
    }

    public function getLogDir()
    {
        return $this->getProjectDir() . '/build/var/logs/' . $this->environment;
    }
}
