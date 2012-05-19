<?php

namespace Bundle\PaymentGatewayBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree.
     *
     * @return \Symfony\Component\DependencyInjection\Configuration\NodeInterface
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('payment_gateway', 'array');
        $rootNode
            ->children()
                ->scalarNode('apiLoginId')->end()
                ->scalarNode('transactionKey')->end()
                ->scalarNode('postUrl')->end()
                ->scalarNode('logger_service')->defaultValue('payment_gateway.logger.null_logger')->end()
                ->scalarNode('logsPath')->end()
            ->end()
        ;

        return $treeBuilder;
    }
}

