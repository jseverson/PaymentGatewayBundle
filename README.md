# Payment Gateway Bundle

This includes a single implementation for a connection to Authorize.NET AIM Service

## Authorize.net

Sign up for a test account to get your test API Login ID and Transaction Key

### config.yml

    payment_gateway.authorizenet:
      config:
        default:
          apiLoginId: xxxxxxxx
          transactionKey: xxxxxxxx
          postUrl: https://test.authorize.net/gateway/transact.dll

### Instantiate

    $gateway = $this->container->get('payment_gateway.authorizenet')->get('default');

### Set Requirements

    $gateway->setAddress($address);
    $gateway->setAmount($amount);
    $gateway->setPaymentMethod($creditCard);
    $gateway->setOrder($order);

### Authorize or Capture
    
    $gateway->authorize();

    or
    
    $gateway->capture();

### Error Checking

    if ($gateway->hasErrors())
    {
	throw new \Exception($gateway->getErrorMessage());
    }

### Get Response Data

    $response = $gateway->getResponse();
    $transactionId = $response->getTransactionId();
    $code = $response->getResponseCodeText();
    $type = $response->getTransactionType());
