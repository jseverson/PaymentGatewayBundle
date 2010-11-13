<?php

namespace Bundle\PaymentGatewayBundle\Transaction\PaymentMethod;

interface CreditCardInterface
{
	public function getExpireMonth();
	public function getExpireYear();
	public function getNumber();
	public function getOwner();
	public function getVerification();
}
