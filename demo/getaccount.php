<?php

require '../paymentgateway.php';

$pg = new Sandiloka\PaymentGateway();
$pg->access_key = 'your_access_key';
$pg->secret_key = 'your_secret_key';

$result = $pg->getAccount();

print_r($result);
echo "\r\n";

?>
