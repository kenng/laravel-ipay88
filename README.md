# Laravel iPay88

iPay88 payment gateway module for Laravel 5.x. Forked from karyamedia/ipay88

**NOTE**: Your require to request demo account from techsupport@ipay88.com.my

## Installation

This plugin can be install via [Composer](https://getcomposer.org/) with following command:

```bash
$ composer require fadlisaad/laravel-ipay88 dev-master
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
		$this->_merchantCode = 'xxxxxx'; //MerchantCode confidential
		$this->_merchantKey = 'xxxxxxxxx'; //MerchantKey confidential
	}

	public function index()
	{
		$request = new IPay88\Payment\Request($this->_merchantKey);
		$this->_data = array(
			'MerchantCode' => $request->setMerchantCode($this->_merchantCode),
			'PaymentId' =>  $request->setPaymentId(1),
			'RefNo' => $request->setRefNo('EXAMPLE0001'),
			'Amount' => $request->setAmount('0.50'),
			'Currency' => $request->setCurrency('MYR'),
			'ProdDesc' => $request->setProdDesc('Testing'),
			'UserName' => $request->setUserName('Your name'),
			'UserEmail' => $request->setUserEmail('email@example.com'),
			'UserContact' => $request->setUserContact('0123456789'),
			'Remark' => $request->setRemark('Some remarks here..'),
			'Lang' => $request->setLang('UTF-8'),
			'Signature' => $request->getSignature(),
			'SignatureType' => $request->setSignatureType('SHA256'),
			'ResponseURL' => $request->setResponseUrl('http://example.com/response'),
			'BackendURL' => $request->setBackendUrl('http://example.com/backend')
			);

		IPay88\Payment\Request::make($this->_merchantKey, $this->_data);
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