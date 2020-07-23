<?php 
use IPay88\RequestForm;

class IPay88
{
	private $merchantKey = null;
	public $merchantCode = null;
	public $responseUrl = null;
	public $backendResponseUrl = null;

	public function __construct($merchantKey, $merchantCode, $responseUrl, $backendResponseUrl)
	{
		$this->merchantKey = $merchantKey;
		$this->merchantCode= $merchantCode;
		$this->responseUrl = $responseUrl;
		$this->backendResponseUrl = $backendResponseUrl;
	}
	
	/**
     * Generate signature to be used for transaction. Updated to use SHA256.
     * Test on https://payment.ipay88.com.my/epayment/testing/testsignature_256.asp
     *
     * @access public
     * @param string $merchantKey ProvidedbyiPay88OPSGandsharebetweeniPay88and merchant only
     * @param string $merchantCode Merchant Code provided by iPay88 and use to uniquely identify the Merchant.
     * @param string $refNo Unique merchant transaction id
     * @param int $amount Payment amount
     * @param string $currency Payment currency
     */
    public function generateSignature($refNo, $amount, $currency)
    {
        $stringToHash = $this->merchantKey.$this->merchantCode.$refNo.$amount.$currency;
        return hash('sha256', $stringToHash);
    }

    /**
    * @access public
    * @param 
    */
    public function makeRequestForm($args)
    {
    	$args['merchantCode'] = $this->merchantCode;
    	$args['signature'] = $this->generateSignature(
    		$args['refNo'],
    		(int) $args['amount'],
    		$args['currency']
    		);
    	$args['responseUrl'] = $this->responseUrl;
    	$args['backendUrl'] = $this->backendResponseUrl;

        return new IPay88\RequestForm($args);
    }
}
