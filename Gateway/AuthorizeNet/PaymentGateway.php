<?php

namespace Bundle\PaymentGatewayBundle\Gateway\AuthorizeNet;

use Bundle\PaymentGatewayBundle\Gateway\PaymentGateway as AbstractPaymentGateway;

class PaymentGateway extends AbstractPaymentGateway
{
	CONST VERSION_KEY        = "x_version";
	CONST DELIM_DATA_KEY     = "x_delim_data";
	CONST DELIM_CHAR_KEY     = "x_delim_char";
	CONST RELAY_RESPONSE_KEY = "x_relay_response";

	private $address;
	private $paymentMethod;
	private $order;
	private $amount;
	
	private $config = array(
		"version"             => "3.1",
		"delimData"           => TRUE,
		"delimChar"           => "|",
		"relayResponse"       => FALSE,
		"curlHeader"          => 0, // set to 0 to eliminate header info from response
		"curlReturnTransfer"  => 1, // Returns response data instead of TRUE(1)
		"curlSslVerifyPeer"   => FALSE,
	);

	private $curl;
	private $response;
	
	public function __construct(array $config = array())
	{
		$this->config = array_merge($this->config, $config);
	}

	public function connect()
	{
		curl_setopt($this->curl, \CURLOPT_HEADER, $this->getCurlOptHeader());
		curl_setopt($this->curl, \CURLOPT_RETURNTRANSFER, $this->getCurlOptReturnTransfer());
		curl_setopt($this->curl, \CURLOPT_SSL_VERIFYPEER, $this->getCurlOptSslVerifyPeer());
	}

	public function disconnect()
	{
		curl_close($this->curl);
	}

	public function authorize()
	{
		$this->connect();
		curl_setopt($this->curl, \CURLOPT_POSTFIELDS, $this->getPostStringForAuthorize());
	
		$this->disconnect();
	}

	public function capture()
	{
		$this->connect();
		curl_setopt($this->curl, \CURLOPT_POSTFIELDS, $this->getPostStringForCapture());

		$this->disconnect();
	}

	public function cancel()
	{
		$this->connect();
		curl_setopt($this->curl, \CURLOPT_POSTFIELDS, $this->getPostStringForCancel());		

		$this->disconnect();
	}

	public function getConfig()
	{
		return $this->config;
	}

	public function getApiLoginId()
	{
		if (array_key_exists('apiLoginId', $this->config)) {
			return $this->config['apiLoginId'];
		}
	}

	public function setApiLoginId($apiLoginId)
	{
		return $this->config['apiLoginId'] = (string) $apiLoginId;
	}

	public function getTransactionKey()
	{
		if (array_key_exists('transactionKey', $this->config)) {
			return $this->config['transactionKey'];
		}
	}

	public function setTransactionKey($transactionKey)
	{
		return $this->config['transactionKey'] = (string) $transactionKey;
	}
	
	public function getVersion()
	{
		if (array_key_exists('version', $this->config)) {
			return $this->config['version'];
		}
	}

	public function setVersion($version)
	{
		return $this->config['version'] = (string) $version;
	}

	public function getDelimData()
	{
		if (array_key_exists('delimData', $this->config)) {
			return $this->config['delimData'];
		}
	}

	public function setDelimData($delimData)
	{
		return $this->config['delimData'] = (bool) $delimData;
	}

	public function getDelimChar()
	{
		if (array_key_exists('delimChar', $this->config)) {
			return $this->config['delimChar'];
		}
	}

	public function setDelimChar($delimChar)
	{
		return $this->config['delimChar'] = (string) $delimChar;
	}

	public function getRelayResponse()
	{
		if (array_key_exists('relayResponse', $this->config)) {
			return $this->config['relayResponse'];
		}
	}

	public function setRelayResponse($relayResponse)
	{
		return $this->config['relayResponse'] = (bool) $relayResponse;
	}
	
	public function getPostUrl()
	{
		if (array_key_exists('postUrl', $this->config)) {
			return $this->config['postUrl'];
		}
	}

	public function setPostUrl($postUrl)
	{
		return $this->config['postUrl'] = (string) $postUrl;
	}

	public function getCurl()
	{
		return $this->curl;
	}

	public function setCurl($curl = null)
	{
		if ($culr) {
			$this->curl = $culr;
		}	else {
			$this->curl = curl_init($this->getPostUrl());
		}
	}

	public function getResponse()
	{
		return $this->response;
	}

	private function curlExec()
	{
		$this->response = curl_exec($this->getCurl());
	}

}
