<?php

require_once 'paymentgateway.php';

class ApiTest extends PHPUnit_Framework_TestCase
{

	protected $api;

	protected function setUp()
	{
		$this->api = new \Sandiloka\PaymentGateway();
		$this->api->access_key = 'MzIyMjkzMzY4';
		$this->api->secret_key = '3aaa4c106513fca8d93e73cee62f407acc6e523a';
		$this->api->production_mode = false;
	}

	protected function tearDown()
	{
		unset($this->api);
	}

	public function testBaseUrl()
	{
		$this->api->production_mode = true;
		$this->assertEquals('https://api.paymentgateway.id/v1', $this->api->base_url());

		$this->api->production_mode = false;
		$this->assertEquals('https://api-sandbox.paymentgateway.id/v1', $this->api->base_url());
	}

	public function testTimestamp()
	{
		$timestamp = $this->api->getApiTimestamp();
	}

	public function testApiHeaders()
	{
		$this->api->production_mode = false;
		$headers = $this->api->getApiHeaders('GET', '/');
		print_r($headers);
		$this->assertCount(5, $headers, 'getApiHeaders failed');
	}

    /**
     * @expectedException Sandiloka\InvalidResponseException
     */
	public function testInvalidResponse()
	{
		$this->api->parseResponse('{a: 0}');
	}

    /**
     * @expectedException Sandiloka\InvalidRequestException
     */
	public function testInvalidRequest()
	{
		$this->api->parseResponse('{"error": true, "error_code": 1, "error_msg": "test"}');
	}

    /**
     * @expectedException Sandiloka\InvalidRequestException
     */
	public function testInvalidAccessKey()
	{
		$this->api->access_key = '';
		$this->api->secret_key = '';		
		$this->api->getBalance();
	}	

	public function testApiGet()
	{
		$data = $this->api->parseResponse( $this->api->api_get($this->api->base_url().'/') );
		$this->assertArrayHasKey('message', $data, '$paymentgateway->auth() gagal');
	}

	public function testAuth()
	{
		$result = $this->api->auth();
		$this->assertArrayHasKey('message', $result, '$paymentgateway->auth() gagal');
	}

	public function testGetAccount()
	{
		$result = $this->api->getAccount();
		print_r($result);
	}

	public function testGetBillers()
	{
		$result = $this->api->getBillers();
		print_r($result);
	}

	public function testGetProducts()
	{
		$result = $this->api->getProducts();
		print_r($result);
	}


}
