<?php

namespace Bundle\PaymentGatewayBundle\Gateway\AuthorizeNet;

use Bundle\PaymentGatewayBundle\Gateway\AbstractPaymentGatewayFactory;

class PaymentGatewayFactory extends AbstractPaymentGatewayFactory
{
	public function createGateway($key)
	{
		if (!array_key_exists($key, $this->config))
		{
			throw new \Exception('Required configuration missing');
		}
		return new PaymentGateway($this->config[$key]);
	}
}
