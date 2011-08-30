<?php

namespace Bundle\PaymentGatewayBundle\Gateway\AuthorizeNet;

use Bundle\PaymentGatewayBundle\Gateway\AbstractPaymentGateway;

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
    CONST EMAIL_KEY          = "x_email";
    CONST METHOD_KEY         = "x_method";
    CONST METHOD_CC_VAL      = "CC";
    CONST PURCHASE_ORDER_KEY  = "x_po_num";
    CONST RELAY_RESPONSE_KEY = "x_relay_response";
    CONST STATE_KEY          = "x_state";
    CONST TRANSACTION_KEY    = "x_tran_key";
    CONST TYPE_KEY           = "x_type";
    CONST TYPE_AUTH_VAL      = "AUTH_ONLY";
    CONST TYPE_CAPTURE_VAL   = "AUTH_CAPTURE";
    CONST VERSION_KEY        = "x_version";
    CONST ZIP_KEY            = "x_zip";

    protected $address;
    protected $amount;
    protected $curl;
    protected $errors = array();
    protected $order;
    protected $paymentMethod;
    protected $postFields = array();
    protected $rawResponse;
    protected $response;

    protected $config = array(
        "version"             => "3.1",
        "delimData"           => TRUE,
        "delimChar"           => "|",
        "relayResponse"       => FALSE,
        "curlHeader"          => 0, // set to 0 to eliminate header info from response
        "curlReturnTransfer"  => 1, // Returns response data instead of TRUE(1)
        "curlSslVerifyPeer"   => FALSE,
        "description"         => "Sample Transaction",
    );

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
        curl_setopt($this->getCurl(), \CURLOPT_HEADER, $this->getCurlOptHeader());
        curl_setopt($this->getCurl(), \CURLOPT_RETURNTRANSFER, $this->getCurlOptReturnTransfer());
        curl_setopt($this->getCurl(), \CURLOPT_SSL_VERIFYPEER, $this->getCurlOptSslVerifyPeer());
    }


    public function disconnect()
    {
        curl_close($this->curl);
    }

    public function authorize()
    {
        $this->addPostField(static::TYPE_KEY, static::TYPE_AUTH_VAL);
        $this->addConnectionToPost();
        $this->addAmountToPost();
        $this->addPaymentMethodToPost();
        $this->addAddressToPost();
        //$this->addOrderToPost();
        $postFields = $this->createEncodedPostFields();

        $this->connect();
        curl_setopt($this->getCurl(), \CURLOPT_POSTFIELDS, $postFields);
        $this->curlExec();
        $this->processResponse();
        $this->disconnect();
    }

    public function capture()
    {
        $this->addPostField(static::TYPE_KEY, static::TYPE_CAPTURE_VAL);
        $this->addConnectionToPost();
        $this->addAmountToPost();
        $this->addPaymentMethodToPost();
        $this->addAddressToPost();
        $this->addOrderToPost();
        $postFields = $this->createEncodedPostFields();

        $this->connect();
        curl_setopt($this->getCurl(), \CURLOPT_POSTFIELDS, $postFields);
        $this->curlExec();
        $this->processResponse();
        $this->disconnect();
    }

    /**
     * TODO
     */
    public function cancel()
    {

    }

    public function setErrors(array $errors = array())
    {
        $this->errors = $errors;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function hasErrors()
    {
        return !empty($this->errors);
    }

    public function getErrorMessage()
    {
        $out = '';
        foreach ($this->errors as $error)
        {
            $out .= $error.', ';
        }
        $out = rtrim( $out, ", " );
        return $out;
    }

    private function curlExec()
    {
        $this->rawResponse = curl_exec($this->getCurl());
    }

    public function createEncodedPostFields()
    {
        $out = '';
        foreach ($this->postFields as $key => $val)
        {
            $out .= $key. '=' . urlencode($val) . "&";
        }
        $out = rtrim( $out, "& " );
        return $out;
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
        if ($curl)
        {
            $this->curl = $culr;
        }
        else
        {
            if (null === $this->getPostUrl())
            {
                throw new \Exception('POST URL is Required');
            }
            $this->curl = curl_init($this->getPostUrl());
        }
    }

    public function getCurl()
    {
        if (!$this->curl)
        {
            $this->setCurl();
        }
        return $this->curl;
    }

    public function getCurlOptHeader()
    {
        if (array_key_exists('curlHeader', $this->config))
        {
            return $this->config['curlHeader'];
        }
    }

    public function getCurlOptReturnTransfer()
    {
        if (array_key_exists('curlReturnTransfer', $this->config))
        {
            return $this->config['curlReturnTransfer'];
        }
    }

    public function getCurlOptSslVerifyPeer()
    {
        if (array_key_exists('curlSslVerifyPeer', $this->config))
        {
            return $this->config['curlSslVerifyPeer'];
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

    public function setPostFields(array $postFields = array())
    {
        $this->postFields = $postFields;
    }

    public function getPostFields()
    {
        return $this->postFields;
    }

    protected function addPostField($key, $val)
    {
        $this->postFields[$key] = $val;
    }

    protected function removePostField($key)
    {
        if (array_key_exists($key, $this->postFields))
        {
            unset($this->postFields[$key]);
        }
    }

    protected function addAddressToPost()
    {
        if(null === $this->getAddress()) {
            return;
        }

        if($this->getAddress()->getFirstName()) {
            $this->addPostField(static::FIRST_NAME_KEY, $this->getAddress()->getFirstName());
        }
        if($this->getAddress()->getLastName()) {
            $this->addPostField(static::LAST_NAME_KEY, $this->getAddress()->getLastName());
        }
        if($this->getAddress()->getStreet()) {
            $this->addPostField(static::ADDRESS_KEY, $this->getAddress()->getStreet());
        }
        if($this->getAddress()->getState()) {
            $this->addPostField(static::STATE_KEY, $this->getAddress()->getState());
        }
        if($this->getAddress()->getPostalCode()) {
            $this->addPostField(static::ZIP_KEY, $this->getAddress()->getPostalCode());
        }
        if($this->getAddress()->getEmail()) {
            $this->addPostField(static::EMAIL_KEY, $this->getAddress()->getEmail());
        }
    }

    protected function addAmountToPost()
    {
        if (null === $this->getAmount()) {
            throw new \Exception('Amount Required');
        }
        $this->addPostField(static::AMOUNT_KEY, $this->getAmount());
    }

    protected function addConnectionToPost()
    {
        if (null === $this->getApiLoginId()) {
            throw new \Exception('API Login ID Required');
        }
        if (null === $this->getTransactionKey()) {
            throw new \Exception('Transaction Key Required');
        }
        if (null === $this->getVersion()) {
            throw new \Exception('Version Required');
        }
        if (null === $this->getDelimData()) {
            throw new \Exception('Data Delimiter Required');
        }
        if (null === $this->getDelimChar()) {
            throw new \Exception('Character Delimiter Required');
        }
        if (null === $this->getRelayResponse()) {
            throw new \Exception('Relay Response type Required');
        }
        if (null === $this->getDescription()) {
            throw new \Exception('Description Required');
        }
        $this->addPostField(static::API_LOGIN_ID_KEY, $this->getApiLoginId());
        $this->addPostField(static::TRANSACTION_KEY, $this->getTransactionKey());
        $this->addPostField(static::VERSION_KEY, $this->getVersion());
        $this->addPostField(static::DELIM_DATA_KEY, $this->getDelimData());
        $this->addPostField(static::DELIM_CHAR_KEY, $this->getDelimChar());
        $this->addPostField(static::RELAY_RESPONSE_KEY, $this->getRelayResponse());
        $this->addPostField(static::DESC_KEY, $this->getDescription());
    }

    protected function addOrderToPost()
    {
        if (null === $this->getOrder())
        {
            throw new \Exception('Order Required');
        }
        if (null === $this->getOrder()->getNumber())
        {
            throw new \Exception('Order Number Required');
        }
        $this->addPostField(static::PURCHASE_ORDER_KEY, $this->getOrder()->getNumber());
    }

    protected function addPaymentMethodToPost()
    {
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
        $this->addPostField(static::METHOD_KEY, static::METHOD_CC_VAL);
        $this->addPostField(static::CC_NUM_KEY, $this->getPaymentMethod()->getNumber());

        $expireMonth = $this->getPaymentMethod()->getExpireMonth();
        $expireYear  = $this->getPaymentMethod()->getExpireYear();

        if (1 == strlen($expireMonth)) {
            $expireMonth = "0".$expireMonth;
        }
        if (strlen($expireYear) > 2) {
            $expireYear = substr($expireYear, (strlen($expireYear) - 2));
        }
        $expireDate = $expireMonth.$expireYear;
        $this->addPostField(static::CC_EXP_DATE, $expireDate);
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

    public function getRawResponse()
    {
        return $this->rawResponse;
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

    public function processResponse()
    {
        if ($this->rawResponse)
        {
            $this->response = new Response();
            $this->response->setDelimChar($this->getDelimChar());
            $this->response->setRawData($this->rawResponse);
            $this->response->process();
            if ($this->response->hasErrors())
            {
                $this->setErrors($this->response->getErrors());
            }
        }
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
