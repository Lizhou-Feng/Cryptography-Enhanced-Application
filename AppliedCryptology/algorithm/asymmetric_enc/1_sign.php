<?php
//data you want to sign
$file = isset($argv[1]) ? $argv[1] : "";
$out  = isset($argv[2]) ? $argv[2] : "server.sign";

if(!is_file($file)) {
    echo "$file does not exist!\n";
    exit(1);
}
$data = file_get_contents($file);

// read private and public key
$cwd      = dirname(__FILE__);
$priv_key = openssl_pkey_get_private("file://$cwd/server.key");

//create signature
openssl_sign($data, $signature, $priv_key, OPENSSL_ALGO_SHA256);

file_put_contents($out, $signature);

