<?php

namespace Bundle\PaymentGatewayBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle as BaseBundle;

class PaymentGatewayBundle extends BaseBundle
{
    public function getNamespace()
    {
        return __NAMESPACE__;
    }

    public function getPath()
    {
        return strtr(__DIR__, '\\', '/');
    }
}
