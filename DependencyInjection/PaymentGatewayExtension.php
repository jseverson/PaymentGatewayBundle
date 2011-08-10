<?php

namespace Bundle\PaymentGatewayBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;

class PaymentGatewayExtension extends Extension
{
    protected $resources = array(
        'authorizenet'   => 'authorizenet.xml',
    );

    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $processor = new Processor();
        $config = $processor->process($configuration->getConfigTree(), $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('authorizenet.xml');

        if (empty($config['gateways'])) {
            throw new InvalidArgumentException('You must define at least one payment gateway');
        }

        if (empty($config['default_gateway'])) {
            $keys = array_keys($config['gateways']);
            $config['default_gateway'] = reset($keys);
        }

        $clientIdsByName = $this->loadClients($config['clients'], $container);

        $container->setAlias('payment_gateway.client', sprintf('foq_elastica.client.%s', $config['default_client']));
    }

    /**
     * Loads the configured clients.
     *
     * @param array $config An array of clients configurations
     * @param ContainerBuilder $container A ContainerBuilder instance
     */
    protected function loadClients(array $clients, ContainerBuilder $container)
    {
        $clientIds = array();
        foreach ($clients as $name => $clientConfig) {
            $clientDef = new Definition('%foq_elastica.client.class%', array($clientConfig));
            $clientId = sprintf('foq_elastica.client.%s', $name);
            $container->setDefinition($clientId, $clientDef);
            $clientIds[$name] = $clientId;
        }

        return $clientIds;
    }

    /**
     * Returns the recommended alias to use in XML.
     *
     * This alias is also the mandatory prefix to use when using YAML.
     *
     * @return string The alias
     */
    public function getAlias()
    {
        return 'payment_gateway';
    }

}
