<?php

namespace Bundle\PaymentGatewayBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator,
    Symfony\Component\DependencyInjection\ContainerBuilder,
    Symfony\Component\DependencyInjection\Loader\XmlFileLoader,
    Symfony\Component\HttpKernel\DependencyInjection\Extension;

class PaymentGatewayExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('authorizenet.xml');
        $loader->load('logger.xml');
        $loader->load('logs_warmer.xml');

        $container->setAlias('payment_gateway.logger_service', $config['logger_service']);
        $container->setParameter('payment_gateway.authorizenet.config', $config);
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
