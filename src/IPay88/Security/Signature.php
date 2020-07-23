<?php

namespace IPay88\Security;

class Signature
{
	/**
     * Generate signature to be used for transaction.
     *
     * You may verify your signature with online tool provided by iPay88
     * https://payment.ipay88.com.my/epayment/testing/testsignature_response_256.asp
     *
     * @access public
     * 
     * accept arbitary amount of params
     * @example IPay88\Security\Signature::generateSignature($key,$code,$refNo,$amount,$currency,[, $status])
     */
    public static function generateSignature()
    {
        $stringToHash = implode('',func_get_args());
        return hash('sha256', $stringToHash);
    }
}
