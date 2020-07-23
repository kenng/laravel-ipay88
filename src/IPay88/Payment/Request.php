<?php 

namespace IPay88\Payment;

use IPay88\Security\Signature;
use IPay88\View\RequestForm;

class Request
{
    public static $paymentUrl = 'https://payment.ipay88.com.my/epayment/entry.asp';

	private $merchantKey;

	public function __construct($merchantKey)
    {
    	$this->merchantKey = $merchantKey;
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

	private $paymentId;
	public function getPaymentId()
	{
		return $this->paymentId;
	}
	public function setPaymentId($val)
	{
		return $this->paymentId = $val;
	}

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

	private $signature;
	public function getSignature($refresh = false)
	{
		//simple caching
		if((!$this->signature) || $refresh)
		{
			$this->signature = Signature::generateSignature(
				$this->merchantKey,
				$this->getMerchantCode(),
				$this->getRefNo(),
				preg_replace('/[\.\,]/', '', $this->getAmount()), //clear ',' and '.'
				$this->getCurrency()
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
	public static function make($merchantKey, $fieldValues)
	{
		$request = new Request($merchantKey);
		RequestForm::render($fieldValues, self::$paymentUrl);
	}
}