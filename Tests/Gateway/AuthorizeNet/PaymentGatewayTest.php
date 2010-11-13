<?php

namespace Bundle\PaymentGatewayBundle\Service\AuthorizeNet;

class PaymentGatewayTest extends \PHPUnit_Framework_TestCase {

	protected $paymentGateway;
	protected $config = array(
		"version"              => "3.1",
		"delimData"           => TRUE,
		"delimChar"           => "|",
		"relayResponse"       => FALSE,
		"curlHeader"          => 0,
		"curlReturnTransfer" => 1,
		"curlSslVerifyPeer" => FALSE,
	);

	public function setUp() {
		parent::setUp();	
		$this->paymentGateway = new PaymentGateway();
	}

	public function tearDown() {		
		parent::tearDown();
		$this->paymentGateway = null;
		$this->config = null;
	}

	public function testImplementsPaymentGatewayInterface() {
		$this->assertTrue($this->paymentGateway instanceof \Bundle\PaymentGatewayBundle\Service\PaymentGatewayInterface);
	}

	public function testConstructor() {
		$this->assertEquals($this->config, $this->paymentGateway->getConfig());
	}

	public function testGetSetApiLoginId() {
		$this->assertNull($this->paymentGateway->getApiLoginId());
		$value = '111111';
		$this->paymentGateway->setApiLoginId($value);
		$this->assertEquals($value, $this->paymentGateway->getApiLoginId());
		$config = array(
			'apiLoginId' => '222222',
		);
		$gateway = new PaymentGateway($config);
		$this->assertEquals($config['apiLoginId'], $gateway->getApiLoginId());
	}

	public function testGetSetTransactionKey() {
		$this->assertNull($this->paymentGateway->getTransactionKey());
		$value = '111111';
		$this->paymentGateway->setTransactionKey($value);
		$this->assertEquals($value, $this->paymentGateway->getTransactionKey());
		$config = array(
			'transactionKey' => '222222',
		);
		$gateway = new PaymentGateway($config);
		$this->assertEquals($config['transactionKey'], $gateway->getTransactionKey());
	}

	public function testGetSetVersion() {
		$this->assertNotNull($this->paymentGateway->getVersion());
		$value = '3.2';
		$this->paymentGateway->setVersion($value);
		$this->assertEquals($value, $this->paymentGateway->getVersion());
		$config = array(
			'version' => '3.2',
		);
		$gateway = new PaymentGateway($config);
		$this->assertEquals($config['version'], $gateway->getVersion());
	}

	public function testGetSetDelimData() {
		$this->assertTrue($this->paymentGateway->getDelimData());
		$value = FALSE;
		$this->paymentGateway->setDelimData($value);
		$this->assertEquals($value, $this->paymentGateway->getDelimData());
		$config = array(
			'delimData' => FALSE,
		);
		$gateway = new PaymentGateway($config);
		$this->assertEquals($config['delimData'], $gateway->getDelimData());
	}

	public function testGetSetDelimChar() {
		$this->assertNotNull($this->paymentGateway->getDelimChar());
		$value = '~';
		$this->paymentGateway->setDelimChar($value);
		$this->assertEquals($value, $this->paymentGateway->getDelimChar());
		$config = array(
			'delimChar' => '~',
		);
		$gateway = new PaymentGateway($config);
		$this->assertEquals($config['delimChar'], $gateway->getDelimChar());
	}

	public function testGetSetRelayResponse() {
		$this->assertFalse($this->paymentGateway->getRelayResponse());
		$value = TRUE;
		$this->paymentGateway->setRelayResponse($value);
		$this->assertEquals($value, $this->paymentGateway->getRelayResponse());
		$config = array(
			'relayResponse' => TRUE,
		);
		$gateway = new PaymentGateway($config);
		$this->assertEquals($config['relayResponse'], $gateway->getRelayResponse());
	}

	public function testGetSetPostUrl() {
		$this->assertNull($this->paymentGateway->getPostUrl());
		$value = 'http://www.google.com';
		$this->paymentGateway->setPostUrl($value);
		$this->assertEquals($value, $this->paymentGateway->getPostUrl());
		$config = array(
			'postUrl' => 'http://www.google.com',
		);
		$gateway = new PaymentGateway($config);
		$this->assertEquals($config['postUrl'], $gateway->getPostUrl());
	}

	

}
