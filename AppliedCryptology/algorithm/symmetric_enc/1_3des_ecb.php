<?php
$key    = "this is a secret key";
$input1 = "abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz123456789012 hello vulnerable ecb";
$input2 = "abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz123456789012"; // 64 byte long
$input3 = "aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa hello vulnerable ecb";
$input4 = "aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaahello vulnerable ecb";
$encyprted1 = "aacef0cef8450657af223aa3e1104fe168e1389cb2c9c31b4d7e0f950b972885fb59262358f6508355346d5b7940e6b9f8ec4c270c8fc923fc069cbc0d317dfbb9bf8ae549b289ec2ff2cbd188c94beaaaac25d026dc28f7";
$encyprted2 = "aacef0cef8450657af223aa3e1104fe168e1389cb2c9c31b4d7e0f950b972885fb59262358f6508355346d5b7940e6b9f8ec4c270c8fc923fc069cbc0d317dfb";
$encyprted3 = "7ec97505b3e5fe5b7ec97505b3e5fe5b7ec97505b3e5fe5b7ec97505b3e5fe5b7ec97505b3e5fe5b7ec97505b3e5fe5b7ec97505b3e5fe5b7ec97505b3e5fe5bb9bf8ae549b289ec2ff2cbd188c94beaaaac25d026dc28f7";
$encyprted4 = "7ec97505b3e5fe5b"; // 8 byte long

if(!function_exists("hex2bin")) { // PHP 5.4起引入的hex2bin
    function hex2bin($data) {
        return pack("H*", $data);
    }
}

// ECB模式加密用不到IV，CBC模式才会用到IV
// 所以IV不管如何随机变化，ECB模式下完全不受IV变化的影响，固定明文输入，确定密文输出
$td = mcrypt_module_open('tripledes', '', 'ecb', '');
$block_size = mcrypt_enc_get_block_size($td);
$iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_DEV_RANDOM);
//var_dump(bin2hex($iv));
mcrypt_generic_init($td, $key, $iv);
$encrypted_data1 = mcrypt_generic($td, $input1);
$encrypted_data2 = mcrypt_generic($td, $input2);
$encrypted_data3 = mcrypt_generic($td, $input3);
$encrypted_data4 = mcrypt_generic($td, $input4);
$plaintext1      = mdecrypt_generic($td, hex2bin($encyprted1));
$plaintext2      = mdecrypt_generic($td, hex2bin($encyprted2));
$plaintext3      = mdecrypt_generic($td, hex2bin($encyprted3));
$plaintext4      = mdecrypt_generic($td, hex2bin($encyprted4));
mcrypt_generic_deinit($td);
mcrypt_module_close($td);
print_r($block_size."\n");
print_r(bin2hex($encrypted_data1)."\n");
print_r(bin2hex($encrypted_data2)."\n");
print_r(bin2hex($encrypted_data3)."\n");
print_r(bin2hex($encrypted_data4)."\n");
print_r($plaintext1."\n");
print_r($plaintext2."\n");
print_r($plaintext3."\n");
print_r($plaintext4."\n");
