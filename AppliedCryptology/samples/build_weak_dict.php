<?php
/**
 * @description
 * @author huangwei
 */
$dict      = isset($argv[1]) ? $argv[1] : "";
if(!is_file($dict)) {
    echo "$dict not exist!\n";
    exit(1);
}

$passwords = preg_split('/\n/', file_get_contents($dict));
$pass_arr  = array();

foreach($passwords as $pass) {
    $pass_arr[$pass] = 1;
}

file_put_contents($dict . ".json", json_encode($pass_arr));

