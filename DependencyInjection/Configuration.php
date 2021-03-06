<?php

namespace JC\ConsulApiClientBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('jc_consul_api_client');

        $rootNode
            ->children()
                ->arrayNode('clients')
                    ->defaultValue([
                        'secure' => false,
                        'host' => 'localhost',
                        'port' => 8500,
                        'logger' => null,
                        'port' => 8500,
                        'secret' => null,
                    ])
                    ->requiresAtLeastOneElement()
                    ->prototype('array')
                        ->children()
                            ->booleanNode('secure')
                                ->defaultFalse()
                            ->end()   
                            ->scalarNode('host')
                                ->isRequired()
                                ->cannotBeEmpty()
                            ->end()
                            ->scalarNode('logger')->end()
                            ->integerNode('port')
                                ->beforeNormalization()
                                    ->ifString()
                                    ->then(function ($value) { return (int) $value; })
                                ->end() 
                                ->defaultValue(8500)
                                ->cannotBeEmpty()
                            ->end()
                            ->scalarNode('secret')
                                ->defaultValue(null)
                            ->end()    
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
