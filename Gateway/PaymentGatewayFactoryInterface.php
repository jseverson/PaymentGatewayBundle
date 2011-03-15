<?php

namespace Bundle\PaymentGatewayBundle\Gateway;

interface PaymentGatewayFactoryInterface
{
    public function createGateway($key);
    public function setConfig(array $config = array());
    public function getConfig();
    public function setGateways(array $gateways = array());
    public function getGateways();
    public function get($key);
}
