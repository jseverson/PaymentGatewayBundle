<?php

namespace Bundle\PaymentGatewayBundle\Gateway;

use Bundle\PaymentGatewayBundle\Logger\PaymentLoggerInterface;

abstract class AbstractPaymentGatewayFactory implements PaymentGatewayFactoryInterface
{
    protected $paymentLogger;
    protected $config = array();
    protected $gateways = array();

    public function __construct(PaymentLoggerInterface $paymentLogger, array $config)
    {
        $this->paymentLogger = $paymentLogger;
        $this->config = $config;
    }
}
