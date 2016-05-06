<?php

namespace Hawk\ApiBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('hawk_api');

        $rootNode
            ->children()
                ->arrayNode('client')
                    ->children()
                        ->scalarNode('host')->defaultValue(
                            (PHP_SAPI !== 'cli') ? $_SERVER['HTTP_HOST'] : ''
                        )->end()
                        ->scalarNode('port')->defaultValue(null)->end()
                        ->scalarNode('key')->defaultValue('')->end()
                        ->booleanNode('https')->defaultValue(false)->end()
                    ->end()
                ->end()
            ->end()
        ;


        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
