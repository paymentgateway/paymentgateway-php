<?php

namespace Sandiloka;

require_once 'exceptions.php';

class PaymentGateway
{
    public $production_mode     = false;
    public $access_key          = '';
    public $secret_key          = '';
    public $useragent           = 'PaymentGateway-PHP (https://github.com/sandiloka/paymentgateway-php)';
    private $initialized        = false;
    private $session            = null;

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
            return 'https://api.paymentgateway.id/v1';
        }
        else
        {
            return 'https://api.sandbox.paymentgateway.id/v1';
        }
    }

    public function getApiTimestamp()
    {
        return date('c');
    }

    public function getApiSignature($httpverb, $url, $timestamp)
    {
        $path       = parse_url($url, PHP_URL_PATH);
        $message    = 'GET'.$path.$timestamp;
        $digest     = hash_hmac('sha256', $message, $this->secret_key);
        $signature  = base64_encode($digest);
        return $signature;
    }

    public function getApiHeaders($httpverb, $url)
    {
        $timestamp = $this->getApiTimestamp();
        $headers = array
        (
            'Accept: application/json',
            'X-API-AccessKey: '.$this->access_key,
            'X-API-Timestamp: '.$timestamp,
            'Authorization: '.$this->getApiSignature('GET', $url, $timestamp),
            'Connection: close'
        );

        return $headers;
    }

    public function parseResponse($response)
    {
        $json = json_decode($response, true);
        if($json == null)
        {
            throw new InvalidResponseException("Response is not a valid json: ".$response, 1);
        }
        else
        {
            if(array_key_exists('error', $json))
            {
                throw new InvalidRequestException($json['error_msg'], $json['error_code']);
            }
        }
        return $json;
    }

    public function api_get($url)
    {
        $headers = $this->getApiHeaders('GET', $url);
        curl_setopt( $this->session, CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $this->session, CURLOPT_URL, $url );
        curl_setopt( $this->session, CURLOPT_HTTPGET, 1);
        $output = curl_exec( $this->session );
        return $output;
    }

    public function api_post($url, $params)
    {
        $headers = $this->getApiHeaders('POST', $url);
        curl_setopt( $this->session, CURLOPT_HTTPHEADER, $headers );
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

    public function auth()
    {
        $url = $this->base_url().'/';
        $response = $this->api_get($url);
        $data = $this->parseResponse($response);
        return $data;
    }

    public function getAccount()
    {
        $url = $this->base_url().'/account';
        $response = $this->api_get($url);
        $data = $this->parseResponse($response);
        return $data;
    }

    public function getBalance()
    {
        $url = $this->base_url().'/account/balance';
        $response = $this->api_get($url);
        $data = $this->parseResponse($response);
        return $data;
    }

    public function getBillers()
    {
        $url = $this->base_url().'/billers';
        $response = $this->api_get($url);
        $data = $this->parseResponse($response);
        return $data;
    }

    public function getBiller($billerid)
    {
        $url = $this->base_url().'/billers/'.$billerid;
        $response = $this->api_get($url);
        $data = $this->parseResponse($response);
        return $data;
    }

    public function getProducts()
    {
        $url = $this->base_url().'/products';
        $response = $this->api_get($url);
        $data = $this->parseResponse($response);
        return $data;
    }

    public function getProduct($productid)
    {
        $url = $this->base_url().'/products/'.$productid;
        $response = $this->api_get($url);
        $data = $this->parseResponse($response);
        return $data;
    }

    public function inquiry($productid, $customerid)
    {
        $params = array('productid'=>$productid, 'customerid'=>$customerid);
        $url = $this->base_url().'/inquiry';
        $response = $this->api_post($url, $params);
        $data = $this->parseResponse($response);
        return $data;
    }

    public function payment($productid, $customerid, $refid)
    {
        $params = array('productid'=>$productid, 'customerid'=>$customerid, 'refid'=>$refid);
        $url = $this->base_url().'/payments';
        $response = $this->api_post($url, $params);
        $data = $this->parseResponse($response);
        return $data;
    }


}

?>