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
                ->arrayNode('entity')
                    ->children()
                        ->scalarNode('class')
                            ->isRequired()
                        ->end()
                        ->scalarNode('serializer')
                            ->isRequired()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('elasticsearch')
                    ->children()
                        ->scalarNode('index_name')
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
                                ->arrayNode('analysis')
                                    ->prototype('variable')
                                        ->treatNullLike([])
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('mapping')
                            ->prototype('variable')
                                ->treatNullLike([])
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
