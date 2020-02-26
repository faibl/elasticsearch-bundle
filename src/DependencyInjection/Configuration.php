<?php

namespace Faibl\ElasticsearchBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('faibl_elasticsearch');
        $rootNode = $treeBuilder->getRootNode();
        $rootNode
            ->children()
                ->scalarNode('index_name')
                    ->defaultValue('fbl_elasticsearch_index')
                    ->isRequired()
                    ->end()
                ->scalarNode('document_type')
                    ->isRequired()
                    ->end()
                ->arrayNode('settings')
                    ->children()
                        ->scalarNode('number_of_shards')
                            ->defaultValue(1)
                            ->isRequired()
                            ->end()
                        ->scalarNode('number_of_replicas')
                            ->defaultValue(0)
                            ->isRequired()
                            ->end()
                    ->end()
                ->end()
                ->arrayNode('mapping')
                    ->prototype('variable')
                    ->treatNullLike([])
                ->end()
            ->end();

        return $treeBuilder;
    }
}
