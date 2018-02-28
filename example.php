<?php


include("src/Client.php");

$key = 'PUBLIC_KEY';
$secret = 'PRIVATE_KEY';
$b = new Client ($key, $secret);
var_dump ($b->getBalances());
echo "\n\n";