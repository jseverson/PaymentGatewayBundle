<?php

namespace Bundle\PaymentGatewayBundle\Gateway;

abstract class AbstractPaymentGatewayFactory implements PaymentGatewayFactoryInterface
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
        return $this->createGateway($key);
    }

}
