<?php

require '../paymentgateway.php';

$pg = new PaymentGateway();
$pg->secret_key = 'sandiloka-test';
$pg->access_key = 'accesskey-test';

$result = $pg->getProducts();

print_r($result);
echo "\r\n";

?>