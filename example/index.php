<?php

require __DIR__ . '/../vendor/autoload.php';
$curl = new \Yuana\Curl();
for ($i=0; $i < 10; $i++) { 
    $response = $curl->get('http://belanja-api.herokuapp.com', ["users_id" => 2]);
}

echo $response;
