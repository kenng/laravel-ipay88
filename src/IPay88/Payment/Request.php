<?php

namespace IPay88\Payment;

use IPay88\Security\Signature;
use IPay88\View\RequestForm;

class Request
{
    public static $paymentUrl = 'https://payment.ipay88.com.my/epayment/entry.asp';

	private $merchantKey;

	/*
     * @param merchantKey key provided by iPay88 OPSG and share between iPay88 and merchant only
	 * @param merchantCode The Merchant Code provided by iPay88 and use to uniquely identify the Merchant.
	 * @param responseUrl URL to redirect to on completed/cancellation of iPay88  payment
	 * @param backendUrl URL for iPay88 to return respones in case responseUrl is not reachable (due to user close the browser and etc)
	 * @param lang Encoding type
	 *		“ISO-8859-1” – English
	 *      “UTF-8” – Unicode
	 *      “GB2312” – Chinese Simplified “GD18030” – Chinese Simplified
	 *      “BIG5” – Chinese Traditional
	 */
	public function __construct(
		string $merchantKey,
		string $merchantCode,
		string $responseUrl,
		string $backendUrl,
		string $lang = 'UTF-8',
		string $signatureType = 'SHA256')
    {
		$this->merchantKey = $merchantKey;
		$this->merchantCode = $merchantCode;
		$this->responseUrl = $responseUrl;
		$this->backendUrl = $backendUrl;
		$this->lang = $lang;
		$this->signatureType = $signatureType;
    }

	private $merchantCode;
	public function getMerchantCode()
	{
		return $this->merchantCode;
	}
	public function setMerchantCode($val)
	{
		$this->signature = null; //need new signature if this is changed
		return $this->merchantCode = $val;
	}

	// optional: if not set, payment gateway will set it
	private $paymentId;
	public function getPaymentId()
	{
		return $this->paymentId;
	}
	public function setPaymentId($val)
	{
		return $this->paymentId = $val;
	}

	// refNo is Unique merchant transaction number / Order ID
	// this will be used in signature generation and verification
	private $refNo;
	public function getRefNo()
	{
		return $this->refNo;
	}

	public function setRefNo($val)
	{
		$this->signature = null; //need new signature if this is changed
		return $this->refNo = $val;
	}

	private $amount;
	public function getAmount()
	{
		return $this->amount;
	}

	public function setAmount($val)
	{
		$this->signature = null; //need new signature if this is changed
		return $this->amount = $val;
	}

	private $currency;
	public function getCurrency()
	{
		return $this->currency;
	}

	public function setCurrency($val)
	{
		$this->signature = null; //need new signature if this is changed
		return $this->currency = $val;
	}

	private $prodDesc;
	public function getProdDesc()
	{
		return $this->prodDesc;
	}
	public function setProdDesc($val)
	{
		return $this->prodDesc = $val;
	}

	private $userName;
	public function getUserName()
	{
		return $this->userName;
	}
	public function setUserName($val)
	{
		return $this->userName = $val;
	}

	private $userEmail;
	public function getUserEmail()
	{
		return $this->userEmail;
	}
	public function setUserEmail($val)
	{
		return $this->userEmail = $val;
	}

	private $userContact;
	public function getUserContact()
	{
		return $this->userContact;
	}

	public function setUserContact($val)
	{
		return $this->userContact = $val;
	}

	private $remark;
	public function getRemark()
	{
		return $this->remark;
	}

	public function setRemark($val)
	{
		return $this->remark = $val;
	}

	private $lang;
	public function getLang()
	{
		return $this->lang;
	}

	public function setLang($val)
	{
		return $this->lang = $val;
	}

	private $signatureType;
	public function getSignatureType()
	{
		return $this->signatureType;
	}

	public function setSignatureType($val)
	{
		return $this->signatureType = $val;
	}

	private $signature;
	public function getSignature(
		string $merchantCode,
		string $refNo,
		string $amount,
		string $currency,
		bool $refresh = false,
	)
	{
		//simple caching
		if((!$this->signature) || $refresh)
		{
			$this->signature = Signature::generateSignature(
				$this->merchantKey,
				$merchantCode ?? $this->getMerchantCode(),
				$refNo ?? $this->getRefNo(),
				preg_replace('/[\.\,]/', '', $amount ?? $this->getAmount()), //clear ',' and '.'
				$currency ?? $this->getCurrency()
			);
		}

		return $this->signature;
	}

	private $responseUrl;
	public function getResponseUrl()
	{
		return $this->responseUrl;
	}
	public function setResponseUrl($val)
	{
		return $this->responseUrl = $val;
	}

	private $backendUrl;
	public function getBackendUrl()
	{
		return $this->backendUrl;
	}
	public function setBackendUrl($val)
	{
		return $this->backendUrl = $val;
	}

	/**
	 * @param userName Customer name
	 * @param userEmail Customer email for receiving receipt
	 * @param userContact Customer contact number
	 * @param remark Merchant remarks
	 * @param lang Encoding type
	 *		“ISO-8859-1” – English
	 *      “UTF-8” – Unicode
	 *      “GB2312” – Chinese Simplified “GD18030” – Chinese Simplified
	 *      “BIG5” – Chinese Traditional
	 */
	public function dataForAPI(
		string $merchantCode,
		string $refNo,
		string $amount,
		string $currency,
		string $prodDesc,
		string $userName,
		string $userEmail,
		string $userContact,
		string $remark,
		int $paymendId = null,
		string $lang = 'UTF-8',
	) : array {
		$theForm = array(
			'MerchantCode' => $merchantCode ?? $this->merchantCode,
			'RefNo' => $refNo ?? $this->refNo,
			'Amount' => $amount?? $this->amount,
			'Currency' => $currency ?? $this->currency,
			'ProdDesc' => $prodDesc ?? $this->prodDesc,
			'UserName' => $userName ?? $this->userName,
			'UserEmail' => $userEmail,
			'UserContact' => $userContact,
			'Remark' => $remark ?? $this->remark,
			'Lang' => $lang,
			'Signature' => $this->getSignature(
				$merchantCode,
				$refNo,
				$amount,
				$currency,
			),
			'SignatureType' => $this->signatureType,
			'ResponseURL' => $this->responseUrl,
			'BackendURL' => $this->backendUrl,
		);

		// optional, if not specified then payment gateway will set its default
		if (isset($paymendId)) {
			$theForm['PaymentId'] = $paymendId;
		}

		return [
			'paymentUrl' => self::$paymentUrl,
			'form' => $theForm,
		];
	}

	protected static $fillable_fields = [
		'merchantCode','paymentId','refNo','amount',
		'currency','prodDesc','userName','userEmail',
		'userContact','remark','lang','responseUrl','backendUrl'
	];

	/**
	* IPay88 Payment Request factory function
	*
	* @access public
	* @param string $merchantKey The merchant key provided by ipay88
	* @param hash $fieldValues Set of field value that is to be set as the properties
	*  Override `$fillable_fields` to determine what value can be set during this factory method
	* @example
	*  $request = IPay88\Payment\Request::make($merchantKey, $fieldValues)
	*
	*/
	public function make($fieldValues, $isSubmitOnLoad=true)
	{
		RequestForm::render($fieldValues, self::$paymentUrl, $isSubmitOnLoad);
	}

}