<?php
//data you want to sign
$file = isset($argv[1]) ? $argv[1] : "";
$sig  = isset($argv[2]) ? $argv[2] : "";

if(!is_file($file)) {
    echo "data $file does not exist!\n";
    exit(1);
}

if(!is_file($sig)) {
    echo "signature file $file does not exist!\n";
    exit(1);
}

$data = file_get_contents($file);

// read private and public key
$cwd      = dirname(__FILE__);
$pub_key  = openssl_pkey_get_public("file://$cwd/server.crt");

$signature = file_get_contents($sig);

//verify signature
$ok = openssl_verify($data, $signature, $pub_key, OPENSSL_ALGO_SHA1);
if ($ok == 1) {
    echo "valid", PHP_EOL;
} elseif ($ok == 0) {
    echo "invalid", PHP_EOL;
} else {
    echo "error: ".openssl_error_string();
}
