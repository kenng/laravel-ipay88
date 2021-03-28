# Laravel iPay88

iPay88 payment gateway module for Laravel 5.x. Forked from karyamedia/ipay88

**NOTE**: Your require to request demo account from techsupport@ipay88.com.my

## Installation

This plugin can be install via [Composer](https://getcomposer.org/) with following command:

```bash
$ composer require kenng/laravel-ipay88 dev-master
```

## Example Controller

```php
<?php

class Payment {

	protected $_merchantCode;
	protected $_merchantKey;

	public function __construct()
	{
		parent::__construct();
		$this->_merchantCode = env(IPAY88_MERCHANT_CODE, 'xxxxxx');
		$this->_merchantKey = env(IPAY88_MERCHANT_KEY, 'xxxxxxxxx');
		$this->responseUrl = 'http://your-website.com/url-redirected-after-payment';
		$this->backendUrl = 'http://your-website.com/backup-url-if-responseurl-fails';
	}

	// if using blade view
	public function index()
	{
		$iRequest = new \IPay88\Payment\Request($this->_merchantKey);
		$this->_data = array(
			'MerchantCode' => $iRequest->setMerchantCode($this->_merchantCode),
			'PaymentId' =>  $iRequest->setPaymentId(1),
			'RefNo' => $iRequest->setRefNo('EXAMPLE0001'),
			'Amount' => $iRequest->setAmount('1.00'),
			'Currency' => $iRequest->setCurrency('MYR'),
			'ProdDesc' => $iRequest->setProdDesc('Testing'),
			'UserName' => $iRequest->setUserName('Your name'),
			'UserEmail' => $iRequest->setUserEmail('email@example.com'),
			'UserContact' => $iRequest->setUserContact('0123456789'),
			'Remark' => $iRequest->setRemark('Some remarks here..'),
			'Lang' => $iRequest->setLang('UTF-8'),
			'Signature' => $iRequest->getSignature(),
			'SignatureType' => $iRequest->setSignatureType('SHA256'),
			'ResponseURL' => $iRequest->setResponseUrl('http://example.com/response'),
			'BackendURL' => $iRequest->setBackendUrl('http://example.com/backend')
			);

		IPay88\Payment\Request::make($this->_merchantKey, $this->_data);
	}

	public function getForAPI()
	{
		$iRequest = new \IPay88\Payment\Request(
			$this->_merchantKey,
			$this->_merchantCode,
			$this->responseUrl,
			$this->backendUrl,
		);
		return $iRequest->dataForAPI(
			$this->_merchantCode,
			'Reference Number or unique order number',	// reference number
			'0.50',										// amount
			'MYR', 										// currency
			'product description',
			'customer name',
			'customer email',
			'customer contact',
			'merchant remark',
			'UTF-8',
		);
	}

	public function response()
	{
		$response = (new IPay88\Payment\Response)->init($this->_merchantCode);
		return $response;
	}
}
```

## Credits

[Leow Kah Thong](https://github.com/ktleow)

[Fikri Marhan](https://github.com/fikri-marhan)

[Pijoe](https://github.com/pijoe86)

[aa6my](https://github.com/aa6my)

## Reference
https://github.com/cchitsiang/ipay88

https://github.com/fastsafety/ipay88

## Lisence

MIT &copy; [Apadmedia](https://github.com/fadlisaad). Please see [License File](LICENSE.md) for more information.
