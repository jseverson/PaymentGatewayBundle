<?php

namespace Bundle\PaymentGatewayBundle\Gateway;

abstract class AbstractPaymentGatewayFactory
{
	protected $config = array();
	protected $gateways = array();

	abstract public function createGateway($key);

	public function setConfig(array $config = array())
	{
		$this->config = $config;
	}

	public function getConfig()
	{
		return $this->config;
	}

	public function setGateways(array $gateways = array())
	{
		$this->gateways = $gateways;
	}

	public function getGateways()
	{
		return $this->gateways;
	}

	public function get($key)
	{
		if (!array_key_exists($key, $this->gateways))
		{
			$this->gateways[$key] = $this->createGateway($key);
		}
		return $this->gateways[$key];
	}

}
