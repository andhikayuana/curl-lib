<?php

require __DIR__ . '/../vendor/autoload.php';

$curl = new \Yuana\Curl();
$response = $curl->get('http://belanja-api.herokuapp.com');

echo $response;