<?php

class PaymentGateway
{
	public (boolean) $production_mode = false;
	public (string) $access_key		= '';
	public (string) $secret_key		= '';
	private $session;
	private (boolean) $initialized	= false;

	function __construct()
	{
		if(!extension_loaded('curl'))
		{
			die('PaymentGateway library requires CURL. Please install PHP5 Curl extension');
		}		
		$this->init();
	}

	function init()
	{
		$this->session = curl_init();
		curl_setopt( $this->session, CURLOPT_COOKIEJAR, 'cookiejar' );
		curl_setopt( $this->session, CURLOPT_COOKIEFILE, 'cookiejar' );
		curl_setopt( $this->session, CURLOPT_USERAGENT, $this->useragent);
		curl_setopt( $this->session, CURLOPT_SSL_VERIFYHOST, 0 );
		curl_setopt( $this->session, CURLOPT_SSL_VERIFYPEER, 0 );
		curl_setopt( $this->session, CURLOPT_FOLLOWLOCATION, 0 );
		curl_setopt( $this->session, CURLOPT_RETURNTRANSFER, 1 );
		$this->initialized = TRUE;
	}	

	public function http_get($url)
	{
		curl_setopt( $this->session, CURLOPT_URL, $url );
		curl_setopt( $this->session, CURLOPT_HTTPGET, 1);
		$output = curl_exec( $this->session );
		return $output;
	}

	public function http_post($url, $params)
	{
		curl_setopt( $this->session, CURLOPT_FOLLOWLOCATION, 0 );
		curl_setopt( $this->session, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $this->session, CURLOPT_URL, $url );
		if (count($params) > 0)
		{
			curl_setopt( $this->session, CURLOPT_POSTFIELDS, http_build_query($params) );
		}
		curl_setopt( $this->session, CURLOPT_POST, 1 );
		$output = curl_exec( $this->session );
		return $output;
	}

	public function biller_inquiry($billerid, $productid, $customerid, $invoiceid)
	{

	}

	public function biller_payment($billerid, $productid, $customerid, $invoiceid)
	{

	}

	public function biller_topup($billerid, $productid, $customerid, $amount)
	{

	}


}

?>