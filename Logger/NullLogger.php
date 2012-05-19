<?php

namespace Bundle\PaymentGatewayBundle\Logger;

use Bundle\PaymentGatewayBundle\Logger\PaymentLoggerInterface;

class NullLogger implements PaymentLoggerInterface
{
    public function log($message)
    {
        return true;
    }
}
