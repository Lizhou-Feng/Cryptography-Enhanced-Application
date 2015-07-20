<?php
/**
 * @description RSA非对称加密demo
 * @author huangwei
 */

$message = 'hello world!';
$pub_key = <<< PUB_KEY
-----BEGIN CERTIFICATE-----
MIIEgjCCA2qgAwIBAgIBADANBgkqhkiG9w0BAQUFADCBjDELMAkGA1UEBhMCQ04x
EDAOBgNVBAgTB0JlaWppbmcxETAPBgNVBAcTCENoYW95YW5nMQwwCgYDVQQKEwND
VUMxCzAJBgNVBAsTAkNTMRYwFAYDVQQDEw1jcy5jdWMuZWR1LmNuMSUwIwYJKoZI
hvcNAQkBFhZodWFuZ3dlaS5tZUBjdWMuZWR1LmNuMB4XDTE0MDcwOTA1NTUwN1oX
DTE1MDcwOTA1NTUwN1owgYwxCzAJBgNVBAYTAkNOMRAwDgYDVQQIEwdCZWlqaW5n
MREwDwYDVQQHEwhDaGFveWFuZzEMMAoGA1UEChMDQ1VDMQswCQYDVQQLEwJDUzEW
MBQGA1UEAxMNY3MuY3VjLmVkdS5jbjElMCMGCSqGSIb3DQEJARYWaHVhbmd3ZWku
bWVAY3VjLmVkdS5jbjCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBAO5x
/bTWTu4WLYSVaO8VaW8dB9oKOVYDVUV0jBwA0jveYoVM1g8/NAgnx2AtSKhG703W
8b9y+WV9UD+UnCx6yOyuCoft1iXs+4ELkNne44FTnuDb06OZ+nQrKDi3QGHW+UHj
qYW/bhgEDMCgubLhhICbSNZBEzf/Vu4KuL8RXP1DL6FrCg5VnKuPDGGb+Lb6oJIX
vQleluN4rZxIw9sN2UOuJ0kDQ6VwCrVV2FV4YR5KE/sKSIRxNzk+eLGd7YWXTXM4
ul6+I3Po7QJ8FCdj5pnbF/xVhFqM4lH3P8FExqONHn2WMmehhG46hgj3NWFaM2Fl
Izt+M8NdXpmMWGN+HJ0CAwEAAaOB7DCB6TAdBgNVHQ4EFgQUEC7yUSmu5kpJLFOS
QEsC+q28Xn4wgbkGA1UdIwSBsTCBroAUEC7yUSmu5kpJLFOSQEsC+q28Xn6hgZKk
gY8wgYwxCzAJBgNVBAYTAkNOMRAwDgYDVQQIEwdCZWlqaW5nMREwDwYDVQQHEwhD
aGFveWFuZzEMMAoGA1UEChMDQ1VDMQswCQYDVQQLEwJDUzEWMBQGA1UEAxMNY3Mu
Y3VjLmVkdS5jbjElMCMGCSqGSIb3DQEJARYWaHVhbmd3ZWkubWVAY3VjLmVkdS5j
boIBADAMBgNVHRMEBTADAQH/MA0GCSqGSIb3DQEBBQUAA4IBAQBEZgGpuoLkBNRn
uZ17nkj+Zk7Go4mokqYoZMdPVUF+v6RZyGiyUJMupJtVhnArdM40Exkaks359XeT
kxq04tU2rhDHlAtjSr9dGMYBLUg0W/kyDXjs9q0JU22Y4eml15WJV9tx9hXTsVG6
9B9F56Dk89vdxEWyfSNHxmYKOMAf5TPIBteWLuBgNySPv7d8NhrmDSCE+yPKTX1M
DoNyugo/KWYE4oiScAd/QJrqHgXmiEBcDPc4cpyDJMbg69de3IBnzL6yECKZZkiw
VL7+TzEjCKnUyOEsIEqvFuxe8zkE6QUjaQkCr5B9qWHUuQWQdZMtvdBr+Xu72skZ
FEvq+GPV
-----END CERTIFICATE-----
PUB_KEY;

// 方法1：硬编码公钥
$result = openssl_public_encrypt($message, $cipher_text, $pub_key); 

echo "硬编码公钥方式加密demo" . PHP_EOL;
var_dump($result);
var_dump(bin2hex($cipher_text));


// 方法2：读取公钥文件
unset($cipher_text); // 重置方法1的输出结果
$pub_key = openssl_get_publickey("file:///". dirname(__FILE__) . "/server.crt");

$result = openssl_public_encrypt($message, $cipher_text, $pub_key); 

echo "读取公钥文件方式加密demo" . PHP_EOL;
var_dump($result);
file_put_contents("3_encrypt.enc", $cipher_text); // 保存供 4_decrypt.php 解密用
var_dump(bin2hex($cipher_text));



