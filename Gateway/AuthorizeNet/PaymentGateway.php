<?php

namespace Bundle\PaymentGatewayBundle\Gateway\AuthorizeNet;

use Bundle\PaymentGatewayBundle\Gateway\PaymentGateway as AbstractPaymentGateway;

class PaymentGateway extends AbstractPaymentGateway
{
	CONST ADDRESS_KEY        = "x_address";
	CONST AMOUNT_KEY         = "x_amount";
	CONST API_LOGIN_ID_KEY   = "x_login";
	CONST CC_NUM_KEY         = "x_card_num";
	CONST CC_EXP_DATE        = "x_exp_date";	
	CONST DELIM_DATA_KEY     = "x_delim_data";
	CONST DELIM_CHAR_KEY     = "x_delim_char";
	CONST DESC_KEY           = "x_description";
	CONST FIRST_NAME_KEY     = "x_first_name";
	CONST LAST_NAME_KEY      = "x_last_name";
	CONST METHOD_KEY         = "x_method";
	CONST METHOD_CC_VAL      = "CC";	
	CONST RELAY_RESPONSE_KEY = "x_relay_response";
	CONST STATE_KEY          = "x_state";
	CONST TRANSACTION_KEY    = "x_tran_key";
	CONST TYPE_KEY           = "x_type";
	CONST TYPE_AUTH_VAL      = "AUTH_ONLY";	
	CONST TYPE_CAPTURE_VAL   = "AUTH_CAPTURE";
	CONST VERSION_KEY        = "x_version";
	CONST ZIP_KEY            = "x_zip";

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
		"description"         => "Sample Transaction",
	);

	private $curl;
	private $response;
	
	public function __construct(array $config = array())
	{
		$this->config = array_merge($this->config, $config);
	}

	public function connect()
	{
		if (null === $this->getCurlOptHeader()) {
			throw new \Exception('CURL Header Setting Required');
		}
		if (null === $this->getCurlOptReturnTransfer()) {
			throw new \Exception('CURL Return Transfer Setting Required');
		}
		if (null === $this->getCurlOptSslVerifyPeer()) {
			throw new \Exception('CURL SSL Verify Peer Setting Required');
		}
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

	/**
	 * TODO
	 */
	public function cancel()
	{

	}
	
	private function curlExec()
	{
		$this->response = curl_exec($this->getCurl());
	}

	static private function encodeKeyVal($key, $val)
	{
		return $key. "=" .urlencode($val) . "&";
	}

	public function setApiLoginId($apiLoginId)
	{
		return $this->config['apiLoginId'] = (string) $apiLoginId;
	}

	public function getApiLoginId()
	{
		if (array_key_exists('apiLoginId', $this->config))
		{
			return $this->config['apiLoginId'];
		}
	}

	public function getConfig()
	{
		return $this->config;
	}

	public function setCurl($curl = null)
	{
		if ($culr)
		{
			$this->curl = $culr;
		} 
		else
		{
			$this->curl = curl_init($this->getPostUrl());
		}
	}

	public function getCurl()
	{
		return $this->curl;
	}

	public function getCurlOptHeader()
	{
		if (array_key_exists('curlHeader', $this->config))
		{
			return $this->config['curlHeader'];
		}
	}

	public function setDelimChar($delimChar)
	{
		return $this->config['delimChar'] = (string) $delimChar;
	}

	public function getDelimChar()
	{
		if (array_key_exists('delimChar', $this->config))
		{
			return $this->config['delimChar'];
		}
	}

	public function setDelimData($delimData)
	{
		return $this->config['delimData'] = (bool) $delimData;
	}

	public function getDelimData()
	{
		if (array_key_exists('delimData', $this->config))
		{
			return $this->config['delimData'];
		}
	}

	public function setDescription($description)
	{
		return $this->config['description'] = $description;
	}

	public function getDescription()
	{
		if (array_key_exists('description', $this->config))
		{
			return $this->config['description'];
		}
	}

	public function getPostStringRequirements()
	{
		$postString = '';
		if (null === $this->getApiLoginId()) {
			throw new \Exception('API Login ID Required');
		}
		$postString .= static::encodeKeyVal(static::API_LOGIN_ID_KEY, $this->getApiLoginId());
		if (null === $this->getTransactionKey()) {
			throw new \Exception('Transaction Key Required');
		}
		$postString .= static::encodeKeyVal(static::encodeKeyValue(static::TRANSACTION_KEY, $this->getTransactionKey());
		if (null === $this->getVersion()) {
			throw new \Exception('Version Required');
		}
		$postString .= static::encodeKeyVal(static::VERSION_KEY, $this->getVersion());
		if (null === $this->getDelimData()) {
			throw new \Exception('Data Delimiter Required');
		}
		$postString .= static::encodeKeyVal(static::DELIM_DATA_KEY, $this->getDelimData();
		if (null === $this->getDelimChar()) {
			throw new \Exception('Character Delimiter Required');
		}
		$postString .= static::encodeKeyVal(static::DELIM_CHAR_KEY, $this->getDelimChar());
		if (null === $this->getRelayResponse()) {
			throw new \Exception('Relay Response type Required');
		}
		$postString .= static::encodeKeyVal(static::RELAY_RESPONSE_KEY, $this->getRelayResponse());
		if (null === $this->getAmount()) {
			throw new \Exception('Amount Required');
		}
		$postString .= static::encodeKeyVal(static::AMOUNT_KEY, $this->getAmount());
		if (null === $this->getDescription()) {
			throw new \Exception('Description Required');
		}
		$postString .= static::encodeKeyVal(static::DESC_KEY, $this->getDescription());
		return $postString;
	}

	public function getPostStringDepsForAuthorizeAndCapture() {
		$postString = '';
		$postString .= $this->getPostStringRequirements();
	
		if (null === $this->getPaymentMethod())
		{
			throw new \Exception('Payment Method Required');
		}
		if (null === $this->getPaymentMethod()->getNumber())
		{
			throw new \Exception('Payment Method Number Required');
		}
		if (null === $this->getPaymentMethod()->getExpireMonth())
		{
			throw new \Exception('Credit Card Expiration Month Required');
		}
		if (null === $this->getPaymentMethod()->getExpireYear())
		{
			throw new \Exception('Credit Card Expiration Year Required');
		}
		$postString .= static::encodeKeyVal(static::METHOD_KEY, static::METHOD_CC_VAL);		
		$postString .= static::encodeKeyVal(static::CC_NUM_KEY, $this->getPaymentMethod()->getNumber());
		
		$expireMonth = $this->getPaymentMethod->getExpireMonth();
		$expireYear  = $this->getPaymentMethod->getExpireYear();		

		if (1 == strlen($expireMonth)) {
			$expireMonth .= "0".$expireMonth;
		}
		if (strlen($expireYear) > 2) {
			$expireYear = substr($expireYear, (strlen($expireYear) - 2)); 
		}
		$expDate = $expireMonth.$expireYear;
		$postString .= static::encodeKeyVal(static::CC_EXP_DATE, $expireDate);

		if (null === $this->getAddress())
		{
			throw new \Exception('Billing Address is Required');
		}
		if (null === $this->getAddress()->getFirstName())
		{
			throw new \Exception('First Name is Required');
		}
		if (null === $this->getAddress()->getLastName())
		{
			throw new \Exception('Last Name is Required');
		}
		if (null === $this->getAddress()->getStreet1())
		{
			throw new \Exception('Street Address is Required');
		}
		if (null === $this->getAddress()->getState())
		{
			throw new \Exception('State is Required');
		}
		if (null === $this->getAddress()->getPostalCode())
		{
			throw new \Exception('Postal Code is Required');
		}
		$postString .= static::encodeKeyVal(static::FIRST_NAME_KEY, $this->getAddress()->getFirstName());
		$postString .= static::encodeKeyVal(static::LAST_NAME_KEY, $this->getAddress()->getLastName());
		$postString .= static::encodeKeyVal(static::ADDRESS_KEY, $this->getAddress()->getStreet1());
		$postString .= static::encodeKeyVal(static::STATE_KEY, $this->getAddress()->getState());
		$postString .= static::encodeKeyVal(static::ZIP_KEY, $this->getAddress()->getPostalCode());
		return $postString;	
	}

	public function getPostStringForAuthorize()
	{
		$postString = '';
		$postString .= $this->getPostStringRequirements();
		$postString .= static::encodeKeyVal(static::TYPE_KEY, static::TYPE_AUTH_VAL);
		$postString .= $this->getPostStringDepsForAuthorizeAndCapture();

		return $postString;
	}

	public function getPostStringForCapture()
	{
		$postString = '';
		$postString .= $this->getPostStringRequirements();
		$postString .= static::encodeKeyVal(static::TYPE_KEY, static::TYPE_CAPTURE_VAL);
		$postString .= $this->getPostStringDepsForAuthorizeAndCapture();

		return $postString;
	}

	public function setPostUrl($postUrl)
	{
		return $this->config['postUrl'] = (string) $postUrl;
	}

	public function getPostUrl()
	{
		if (array_key_exists('postUrl', $this->config))
		{
			return $this->config['postUrl'];
		}
	}

	public function getRelayResponse()
	{
		if (array_key_exists('relayResponse', $this->config))
		{
			return $this->config['relayResponse'];
		}
	}

	public function setRelayResponse($relayResponse)
	{
		return $this->config['relayResponse'] = (bool) $relayResponse;
	}

	public function getResponse()
	{
		return $this->response;
	}

	public function setTransactionKey($transactionKey)
	{
		return $this->config['transactionKey'] = (string) $transactionKey;
	}

	public function getTransactionKey()
	{
		if (array_key_exists('transactionKey', $this->config))
		{
			return $this->config['transactionKey'];
		}
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

}
