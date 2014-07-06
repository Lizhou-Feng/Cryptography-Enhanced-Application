<?php
//data you want to sign
$data = 'my data';

// read private and public key
$details = openssl_pkey_get_details("server.key");
var_dump($details);
$public_key_res = openssl_pkey_get_public($details['key']);

//create signature
openssl_sign($data, $signature, $private_key_res, "sha1WithRSAEncryption");

//verify signature
$ok = openssl_verify($data, $signature, $public_key_res, OPENSSL_ALGO_SHA1);
if ($ok == 1) {
    echo "valid";
} elseif ($ok == 0) {
    echo "invalid";
} else {
    echo "error: ".openssl_error_string();
}
