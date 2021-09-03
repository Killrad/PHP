<?php

$input = $_POST['input2'];

$number= array();
preg_match('/(\d+)(-*)(\d+)/', $input, $number);

$output = "https://sozd.duma.gov.ru/bill/".$number[0]."\r\n";
$response["result"]=$output;
echo json_encode($response);