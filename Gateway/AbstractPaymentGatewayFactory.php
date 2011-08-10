<?php

namespace Bundle\PaymentGatewayBundle\Gateway;

abstract class AbstractPaymentGatewayFactory implements PaymentGatewayFactoryInterface
{
    protected $config = array();
    protected $gateways = array();

    public function __construct(array $config)
    {
        $this->config = $config;
    }

}
