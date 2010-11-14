<?php

namespace Bundle\PaymentGatewayBundle\Gateway\AuthorizeNet;

class Response
{
	const RESPONSE_CODE_APPROVED = 1;
	const RESPONSE_CODE_DECLINED = 2;
	const RESPONSE_CODE_ERROR    = 3;
	const RESPONSE_CODE_HELD     = 4;

	const AVS_A         = 'A';
	const AVS_B         = 'B';
	const AVS_E         = 'E';
	const AVS_G         = 'G';
	const AVS_N         = 'N';
	const AVS_P         = 'P';
	const AVS_R         = 'R';
	const AVS_S         = 'S';
	const AVS_U         = 'U';
	const AVS_W         = 'W';
	const AVS_X         = 'X';
	const AVS_Y         = 'Y';
	const AVS_Z         = 'Z';

	const INDEX_RESPONSE_CODE        = 0;
	const INDEX_RESPONSE_REASON_CODE = 2;
	const INDEX_RESPONSE_REASON_TEXT = 3;
	const INDEX_AVS_RESPONSE         = 5;
	const INDEX_TRANSACTION_ID       = 6;
	const INDEX_TRANSACTION_TYPE     = 11;
	const INDEX_PURCHASE_ORDER_NUM   = 36;
	const INDEX_CCV_CODE             = 38;
	const INDEX_CARD_TYPE            = 51;

	const CCV_CODE_MATCH    = 'M';
	const CCV_CODE_NO_MATCH = 'N';
	const CCV_NOT_PROCESSED = 'P';
	const CCV_NOT_PRESENT   = 'S';
	const CCV_ISSUER_UNABLE = 'U';

	static protected $ccvChoices = array(
		self::CCV_CODE_MATCH    => 'Match',
		self::CCV_CODE_NO_MATCH => 'No Match',
		self::CCV_NOT_PROCESSED => 'Not Processed',
		self::CCV_NOT_PRESENT   => 'Should have been present',
		self::CCV_ISSUER_UNABLE => 'Issuer unable to process request',
	);

	static protected $responseCodeChoices = array(
		self::RESPONSE_CODE_APPROVED => 'Approved',
		self::RESPONSE_CODE_DECLINED => 'Declined',
		self::RESPONSE_CODE_ERROR    => 'Error',
		self::RESPONSE_CODE_HELD     => 'Held for Review',
	);

	static protected $avsChoices = array(
		self::AVS_A => 'Street matches, ZIP does not',
		self::AVS_B => 'Address information not provide for AVS check',
		self::AVS_E => 'AVS error',
		self::AVS_G => 'Non-U.S. Card Issuing Bank',
		self::AVS_N => 'No Match on Street or ZIP',
		self::AVS_P => 'AVS not applicable for this transaction',
		self::AVS_R => 'Retry - System unavailable or timed out',
		self::AVS_S => 'Service not supported by issuer',
		self::AVS_U => 'Address information is unavailable',
		self::AVS_W => 'Nine digit ZIP matches, Street does not',
		self::AVS_X => 'Street and nine digit ZIP match',
		self::AVS_Y => 'Street and five digit ZIP match',
		self::AVS_Z => 'Five digit ZIP matches, Street does not',
	);

	protected $avs;
	protected $code;
	protected $data = array();
	protected $delimChar;
	protected $errors = array();
	protected $rawData;

	public static function isAvsValid($avs)
	{
		return array_key_exists($avs, static::$avsChoices);
	}

	public function setAvs($avs)
	{
		if (FALSE === $this->isAvsValid($avs))
		{
			throw new \InvalidArgumentException($avs.' is not a known AVS Code.');
		}
		$this->avs = $avs;
	}

	public function getAvs()
	{
		return $this->avs;
	}

	public function getAvsResponse() {
		if (array_key_exists(static::INDEX_AVS_RESPONSE, $this->data))
		{
			return $this->data[static::INDEX_AVS_RESPONSE];
		}
	}

	public function getAvsText()
	{
		$avs = $this->getAvs();
		if (isset(static::$avsChoices[$avs]))
		{
			return static::$avsChoices[$avs];
		}
	}

	public function getCcvCode()
	{
		if (array_key_exists(static::INDEX_CCV_CODE, $this->data))
		{
			return $this->data[static::INDEX_CCV_CODE];
		}
	}

	public function getCardType()
	{
		if (array_key_exists(static::INDEX_CARD_TYPE, $this->data))
		{
			return $this->data[static::INDEX_CARD_TYPE];
		}
	}

	public function setData(array $data = array())
	{
		$this->data = $data;
	}

	public function getData()
	{
		return $this->data;
	}

	public function setDelimChar($char)
	{
		$this->delimChar = $char;
	}

	public function getDelimChar()
	{
		return $this->delimChar;
	}

	public function addError($e)
	{
		$this->errors[] = $e;
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

	public function getPurchaseOrderNum()
	{
		if (array_key_exists(static::INDEX_PURCHASE_ORDER_NUM, $this->data))
		{
			return $this->data[static::INDEX_PURCHASE_ORDER_NUM];
		}
	}

	public function setRawData($raw)
	{
		$this->rawData = $raw;
	}

	public function getRawData()
	{
		return $this->rawData;
	}

	public function getResponseCode()
	{
		if (array_key_exists(static::INDEX_RESPONSE_CODE, $this->data))
		{
			return $this->data[static::INDEX_RESPONSE_CODE];
		}
	}

	public function getResponseCodeText()
	{
		$code = $this->getReponseCode();
		if (isset($code) && isset(static::$responeCodeChoices[$code]))
		{
			return static::$responseCodeChoices[$code];
		}
	}

	public function getResponseReasonCode()
	{
		if (array_key_exists(static::INDEX_RESPONSE_REASON_CODE, $this->data))
		{
			return $this->data[static::INDEX_RESPONSE_REASON_CODE];
		}
	}

	public function getResponseReasonText()
	{
		if (array_key_exists(static::INDEX_RESPONSE_REASON_TEXT, $this->data))
		{
			return $this->data[static::INDEX_RESPONSE_REASON_TEXT];
		}
	}

	public function getTransactionId()
	{
		if (array_key_exists(static::INDEX_TRANSACTION_ID, $this->data))
		{
			return $this->data[static::INDEX_TRANSACTION_ID];
		}
	}

	public function getTransactionType()
	{
		if (array_key_exists(static::INDEX_TRANSACTION_TYPE, $this->data))
		{
			return $this->data[static::INDEX_TRANSACTION_TYPE];
		}
	}

	public function process()
	{
		$this->transformRawDataToData();
		if (static::RESPONSE_CODE_APPROVED == $this->getResponseCode())
		{
			return;
		}
		$errorMessage = $this->getResponseReasonText();
		$this->addError($errorMessage);
	}

	public function transformRawDataToData()
	{
		$this->data = explode($this->delimChar, $this->rawData);
	}

}
