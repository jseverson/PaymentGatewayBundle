<?php

namespace Bundle\PaymentGatewayBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;

class PaymentGatewayExtension extends Extension
{
	protected $resources = array(
		'authorizenet'   => 'authorizenet.xml',
	);

	public function load(array $config, ContainerBuilder $container)
	{
		if (!$container->hasDefinition('payment_gateway.authorizenet'))
		{
			$loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
			$loader->load($this->resources['authorizenet']);
		}
		if (isset($config['config']))
		{
			$container->setParameter('payment_gateway.authorizenet.config', $config['config']);
		}
		return $container;
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

	/**
	 * Returns the namespace to be used for this extension (XML namespace).
	 *
	 * @return string The XML namespace
	 */
	public function getNamespace()
	{
		return 'http://symfony.com/schema/dic/payment_gateway';
	}

	/**
	 * Returns the base path for the XSD files.
	 *
	 * @return string The XSD base path
	 */
	public function getXsdValidationBasePath()
	{
		return __DIR__.'/../Resources/config/';
	}
}
