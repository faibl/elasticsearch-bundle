<?php

namespace Faibl\ElasticsearchBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class FaiblElasticsearchExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        $definition = $container->getDefinition('Faibl\ElasticsearchBundle\Search\Manager\SearchManager');
        $definition->setArgument(1, $config['elasticsearch']);

        $definition = $container->getDefinition('Faibl\ElasticsearchBundle\Services\SearchService');
        $definition->setArgument(1, new Reference($config['entity']['serializer']));
        $definition->setArgument(2, $config['entity']['class']);

        $definition = $container->getDefinition('Faibl\ElasticsearchBundle\Command\SearchIndexCommand');
        $definition->setArgument(2, $config['entity']['class']);
    }
}
