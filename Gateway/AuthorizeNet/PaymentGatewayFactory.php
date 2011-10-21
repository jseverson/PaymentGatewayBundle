<?php

namespace Bundle\PaymentGatewayBundle\Gateway\AuthorizeNet;

use Bundle\PaymentGatewayBundle\Gateway\AbstractPaymentGatewayFactory;

class PaymentGatewayFactory extends AbstractPaymentGatewayFactory
{
    public function create()
    {
        return new PaymentGateway($this->paymentLogger, $this->config);
    }
}
