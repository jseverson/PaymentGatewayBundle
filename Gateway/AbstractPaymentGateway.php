<?php

namespace Bundle\PaymentGatewayBundle\Gateway;

use Bundle\PaymentGatewayBundle\Transaction\AddressInterface;
use Bundle\PaymentGatewayBundle\Transaction\OrderInterface;

abstract class AbstractPaymentGateway implements PaymentGatewayInterface
{
	protected $address;
	protected $amount;
	protected $order;
	protected $paymentMethod;

	public function connect()
	{
	
	}

	public function disconnect()
	{

	}

	public function authorize()
	{

	}

	public function capture()
	{

	}
	
	public function cancel()
	{

	}

	public function setAddress(AddressInterface $address)
	{
		$this->address = $address;
	}

	public function getAddress()
	{
		return $this->address;
	}

	public function setAmount($amount)
	{
		$this->amount = $amount;
	}

	public function getAmount()
	{
		return $this->amount;
	}

	public function setPaymentMethod($method)
	{
		$this->paymentMethod = $method;
	}

	public function getPaymentMethod()
	{
		return $this->paymentMethod;
	}

	public function setOrder(OrderInterface $order)
	{
		$this->order = $order;
	}

	public function getOrder()
	{
		return $this->order;
	}
}
