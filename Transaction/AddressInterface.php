<?php

namespace Bundle\PaymentGatewayBundle\Transaction;

interface AddressInterface
{
    public function getFirstName();
    public function getLastName();
    public function getStreet();
    public function getState();
    public function getPostalCode();
    public function getEmail();
}
