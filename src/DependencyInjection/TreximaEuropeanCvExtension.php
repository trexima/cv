<?php

namespace Trexima\EuropeanCvBundle\DependencyInjection;

use Trexima\EuropeanCvBundle\Model\UserInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class TreximaEuropeanCvExtension extends Extension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../../config')
        );
        $loader->load('services.yaml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('trexima_european_cv_upload_url', $config['upload_url']);
        $container->setParameter('trexima_european_cv_upload_dir', $config['upload_dir']);
        $container->setParameter('trexima_european_cv_harvey_url', $config['harvey']['url']);
        $container->setParameter('trexima_european_cv_harvey_username', $config['harvey']['username']);
        $container->setParameter('trexima_european_cv_harvey_password', $config['harvey']['password']);
        $container->setParameter('trexima_european_cv_google_apikey', $config['google']['apikey']);
    }

    public function prepend(ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $configs = $container->getExtensionConfig($this->getAlias());
        $config = $this->processConfiguration($configuration, $configs);

        // Doctrine configuration must be loaded before main app config
        if ($config['user_class']) {
            $container->loadFromExtension('doctrine', [
                'orm' => [
                    'resolve_target_entities' => [
                        UserInterface::class => $config['user_class'],
                    ],
                ],
            ]);
        }
    }
}