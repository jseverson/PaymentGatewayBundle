<?php

namespace Bundle\PaymentGatewayBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class Configuration
{
    /**
     * Generates the configuration tree.
     *
     * @return \Symfony\Component\DependencyInjection\Configuration\NodeInterface
     */
    public function getConfigTree()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('payment_gateway', 'array');

        $this->addGatewaysSection($rootNode);

        return $treeBuilder->buildTree();
    }

    /**
     * Adds the configuration for the "clients" key
     */
    private function addGatewaysSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('gateways')
                    ->useAttributeAsKey('id')
                    ->prototype('array')
                        ->performNoDeepMerging()
                        ->children()
                            ->scalarNode('apiLoginId')->end()
                            ->scalarNode('transactionKey')->end()
                            ->scalarNode('postUrl')->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}

