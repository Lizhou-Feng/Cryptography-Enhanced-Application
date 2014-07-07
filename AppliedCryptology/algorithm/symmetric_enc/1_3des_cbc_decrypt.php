<?php
/**
 * @description
 * @author huangwei
 */
$key       = "this is a secret key";
$iv_hex    = isset($argv[1]) ? $argv[1] : "";
$input_hex = isset($argv[2]) ? $argv[2] : "";

if(!function_exists("hex2bin")) { // PHP 5.4起引入的hex2bin
    function hex2bin($data) {
        return pack("H*", $data);
    }
}

$iv    = hex2bin($iv_hex);
$input = hex2bin($input_hex);

$td = mcrypt_module_open('tripledes', '', 'cbc', '');
mcrypt_generic_init($td, $key, $iv);
$decrypted_data = mdecrypt_generic($td, $input);
mcrypt_generic_deinit($td);
mcrypt_module_close($td);
print_r($decrypted_data."\n");
