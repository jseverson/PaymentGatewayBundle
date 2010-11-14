<?php

namespace Bundle\PaymentGatewayBundle\Gateway\AuthorizeNet;

class Response
{
	const CODE_APPROVED = 1;
	const CODE_DECLINED = 2;
	const CODE_ERROR    = 3;
	const CODE_HELD     = 4;

	const AVS_A         = 'A';
	const AVS_B         = 'B';
	const AVS_C         = 'E';
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
	const INDEX_INVOICE_NUMBER       = 7;
	const INDEX_TRANSACTION_TYPE     = 12;
	const INDEX_TAX_EXEMPT           = 35;
	const INDEX_PURCHASE_ORDER_NUM   = 36;
	const INDEX_CARD_CODE            = 38;
	const INDEX_VERIFICATION_CODE    = 39;
	const INDEX_CARD_TYPE            = 51;

	static protected $codeChoices = array(
		static::CODE_APPROVED => 'Approved',
		static::CODE_DECLINED => 'Declined',
		static::CODE_ERROR    => 'Error',
		static::CODE_HELD     => 'Held for Review',
	);

	static protected $avsChoices = array(
		static::AVS_A => 'Street matches, ZIP does not',
		static::AVS_B => 'Address information not provide for AVS check',
		static::AVS_E => 'AVS error',
		static::AVS_G => 'Non-U.S. Card Issuing Bank',
		static::AVS_N => 'No Match on Street or ZIP',
		static::AVS_P => 'AVS not applicable for this transaction',
		static::AVS_R => 'Retry - System unavailable or timed out',
		static::AVS_S => 'Service not supported by issuer',
		static::AVS_U => 'Address information is unavailable',
		static::AVS_W => 'Nine digit ZIP matches, Street does not',
		static::AVS_X => 'Street and nine digit ZIP match',
		static::AVS_Y => 'Street and five digit ZIP match',
		static::AVS_Z => 'Five digit ZIP matches, Street does not',
	);

	protected $avs;
	protected $code;
	protected $data = array();
	protected $delimChar;
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

	public function getAvsText()
	{
		$avs = $this->getAvs();
		if (isset(static::$avsChoices[$avs]))
		{
			return static::$avsChoices[$avs];
		}
	}

	public static function isCodeValid($code)
	{
		return array_key_exists($code, static::$codeChoices);
	}

	public function setCode($code)
	{
		if (FALSE === $this->isCodeValid($code))
		{
			throw new \InvalidArgumentException($code.' is not a known Response Code.');
		}
		$this->code = $code;
	}

	public function getCode()
	{
		return $this->code;
	}

	public function getCodeText()
	{
		$code = $this->getCode();
		if (isset(static::$codeChoices[$code]))
		{
			return static::$codeChoices[$code];
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

	public function setRawData($raw)
	{
		$this->rawData = $raw;
	}

	public function getRawData()
	{
		return $this->rawData;
	}

	public function transformRawDataToData()
	{
		$this->data = explode($this->delimChar, $this->rawData);
	}

}
