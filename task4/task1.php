<?php
function m2(array $nums){
    return '\''.($nums[1]*2).'\'';
}

$input = $_POST['input1'];

$output = preg_replace_callback("/'(\d+)'/", 'm2', $input);

$response["result"]=$output;
echo json_encode($response);