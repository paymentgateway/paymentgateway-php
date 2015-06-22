Payment Gateway PHP
=================

Payment Gateway API REST Client for PHP

https://docs.paymentgateway.id/api/

This is official payment gateway client library for PHP using Sandiloka Payment Gateway API.

## Init
```php
  require_once 'paymentgateway.php';
  
  $gateway = new \Sandiloka\PaymentGateway();
  $gateway->access_key = 'your_access_key';
  $gateway->secret_key = 'your_secret_key';
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

