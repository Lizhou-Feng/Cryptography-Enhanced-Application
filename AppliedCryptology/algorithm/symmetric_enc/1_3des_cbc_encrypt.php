<?php
$key    = "this is a secret key";
$input  = isset($argv[1]) ? $argv[1] : "";

if(!function_exists("hex2bin")) { // PHP 5.4起引入的hex2bin
    function hex2bin($data) {
        return pack("H*", $data);
    }
}

$td = mcrypt_module_open('tripledes', '', 'cbc', '');
$iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_DEV_RANDOM);
mcrypt_generic_init($td, $key, $iv);
$encrypted_data1 = mcrypt_generic($td, $input);
mcrypt_generic_deinit($td);
mcrypt_module_close($td);
print_r(bin2hex($iv)."\n");
print_r(bin2hex($encrypted_data1)."\n");
