<?php

namespace Bundle\PaymentGatewayBundle\Transaction;

interface AddressInterface
{
	public function getFirstName();
	public function getLastName();
	public function getStreet1();
	public function getCity();
	public function getState();
	public function getPostalCode();
}
