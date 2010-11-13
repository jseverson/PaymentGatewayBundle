<?php

namespace Bundle\PaymentGatewayBundle\Gateway;

use Bundle\PaymentGatewayBundle\Transaction\AddressInterface;
use Bundle\PaymentGatewayBundle\Transaction\OrderInterface;

interface PaymentGatewayInterface
{
	public function connect();
	public function disconnect();
	public function authorize();
	public function capture();
	public function cancel();
	
	public function setAddress(AddressInterface $address);
	public function getAddress();
	public function setAmount($amount);
	public function getAmount();
	public function setOrder(OrderInterface $order);
	public function getOrder();
	public function setPaymentMethod($method);
	public function getPaymentMethod();
}
