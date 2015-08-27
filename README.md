Payment Gateway PHP
=================

Payment Gateway API REST Client for PHP.
[![Build Status](https://travis-ci.org/paymentgateway/paymentgateway-php.svg?branch=master)](https://travis-ci.org/paymentgateway/paymentgateway-php)

https://docs.paymentgateway.id/api/

This is official payment gateway client library for PHP using Sandiloka Payment Gateway API.

## Init
```php
  require_once 'paymentgateway.php';
  
  $gateway = new \Sandiloka\PaymentGateway();
  $gateway->access_key = 'your_access_key';
  $gateway->secret_key = 'your_secret_key';
```

## Get Account 

```php
  $account = $gateway->getAccount();
  print_r($account)
``` 

## Get Balance 

```php
  $balance = $gateway->getBalance();
  echo $balance;
``` 

## Get Billers

```php
  $billers = $gateway->getBillers();
  print_r($billers)
``` 
## Get Biller Data
```php
  $biller = $gateway->getBiller('pln');
  print_r($biller);
```
## Get Products

```php
  $products = $gateway->getProducts();
  print_r($products)
``` 
## Get Product Data
```php
  $product = $gateway->getProduct('pln-1600');
  print_r($product);
```
