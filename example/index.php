<?php

require __DIR__ . '/../vendor/autoload.php';
$startTime = microtime(true);
$curl = new \Yuana\Curl();
for ($i=0; $i < 10; $i++) { 
    $response = $curl->get('http://belanja-api.herokuapp.com', ["users_id" => 2]);
}
$finishTime = microtime(true);

echo "Time elapsed: ". $response;
echo "<br>";  
echo $finishTime - $startTime;