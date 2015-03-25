<?php

class PaymentGateway
{
	public $production_mode 	= false;
	public $access_key			= '';
	public $secret_key			= '';
	public $useragent 			= 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.131 Safari/537.36';
	private $initialized		= false;	
	private $session			= null;

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

	public function base_url()
	{
		if($this->production_mode)
		{
			return 'http://api.paymentgateway.id/v1';
		}
		else
		{
			return 'http://localhost/sandiloka/paymentgateway/api/v1';
		}
	}

	public function api_get($url)
	{
		$timestamp 	= date('YmdHisO');
		$path 		= parse_url($url, PHP_URL_PATH);
		$message   	= 'GET'.$path.$timestamp;
		$digest		= hash_hmac('sha256', $message, $this->secret_key);
		$signature	= base64_encode($digest);
		$headers = array
		(
			'Accept: application/json',
			'X-API-AccessKey: '.$this->access_key,
			'X-API-Timestamp: '.$timestamp,
			'Authorization: '.'hmac '.$signature
		);
		curl_setopt( $this->session, CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $this->session, CURLOPT_URL, $url );
		curl_setopt( $this->session, CURLOPT_HTTPGET, 1);
		$output = curl_exec( $this->session );
		return $output;
	}

	public function api_post($url, $params)
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

	public function test()
	{
		$url = $this->base_url().'/';
		$response = $this->api_get($url);
		$data = json_decode($response, true);
		return $data;			
	}

	public function getAccount()	
	{
		$url = $this->base_url().'/account';		
		$response = $this->api_get($url);		
		$data = json_decode($response, true);
		return $data;		
	}

	public function getBalance()	
	{
		$url = $this->base_url().'/account/balance';		
		$response = $this->api_get($url);		
		$data = json_decode($response, true);
		return $data;		
	}	

	public function getBillers()
	{
		$url = $this->base_url().'/billers';		
		$response = $this->api_get($url);
		$billers = json_decode($response, true);
		return $billers;
	}

	public function getBiller($billerid)
	{
		$url = $this->base_url().'/billers/'.$billerid;		
		$response = $this->api_get($url);
		$data = json_decode($response, true);
		return $data;		
	}

	public function getProducts()
	{
		$url = $this->base_url().'/products';		
		$response = $this->api_get($url);
		$billers = json_decode($response, true);
		return $billers;
	}

	public function getProduct($productid)
	{
		$url = $this->base_url().'/products/'.$productid;		
		$response = $this->api_get($url);
		$data = json_decode($response, true);
		return $data;		
	}

	public function inquiry($billerid, $productid, $customerid, $invoiceid)
	{

	}

	public function payment($billerid, $productid, $customerid, $invoiceid)
	{

	}

	public function topup($billerid, $productid, $customerid, $amount)
	{

	}


}

?>