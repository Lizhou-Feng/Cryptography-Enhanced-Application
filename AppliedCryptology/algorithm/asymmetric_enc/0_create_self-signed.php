<?php
// 以下是最终使用的公钥证书中可以被查看的Distinguished Name（简称：DN）信息
$dn = array(
    "countryName" => "CN",
    "stateOrProvinceName" => "Beijing",
    "localityName" => "Chaoyang",
    "organizationName" => "CUC",
    "organizationalUnitName" => "CS",
    "commonName" => "cs.cuc.edu.cn",  // https应用务必确保和你要使用的站点域名匹配
    "emailAddress" => "huangwei.me@cuc.edu.cn"
);

// 产生公私钥对一套
// 等价openssl命令
// openssl genrsa -out server.key 2048
$privkey = openssl_pkey_new(array(
    'private_key_bits' => 2048,
    'private_key_type' => OPENSSL_KEYTYPE_RSA)
);

// 查看生成的server.key的内容
// openssl x509 -in server.key -text -noout
// 给服务器安装使用的证书一般不使用口令保护，避免每次重启服务器时需要人工输入口令
//openssl_pkey_export($privkey, $pkeyout, "mypassword");
openssl_pkey_export($privkey, $pkeyout);
file_put_contents("server.key", $pkeyout);

// 制作CSR文件：Certificate Signing Request
// 等价openssl命令
// openssl req -new -key server.key -out server.csr
$csr = openssl_csr_new($dn, $privkey);

// 对CSR文件进行自签名（第2个参数设置为null，否则可以设置为CA的证书路径），设置证书有效期：365天
$sscert = openssl_csr_sign($csr, null, $privkey, 365);
// 以上所有代码的等价单行openssl命令
// openssl req -x509 -newkey rsa:2048 -keyout server.key -out server.crt -days 365

// 如果不再需要使用，应尽快释放私钥资源，防止针对服务器内存明文私钥数据的直接非法访问
openssl_pkey_free($privkey);

openssl_csr_export($csr, $csrout);
// 查看生成的server.csr的内容
// openssl req -in server.csr -noout -text
// 验证生成的server.csr格式是否合法
// openssl req -verify -in server.csr -noout -text
file_put_contents("server.csr", $csrout);

/*
 * ref: https://www.sslshopper.com/ssl-converter.html
 * PEM格式是CA颁发机构最常使用的证书格式。PEM证书文件的常用扩展名包括：.pem, .crt, .cer和.key。
 * PEM格式文件内容采用Base64编码为ASCII文本，并使用
 * "-----BEGIN CERTIFICATE-----" 和 "-----END CERTIFICATE-----"包围编码之后的文本内容。
 * 服务器证书、中间CA证书、私钥都可以使用PEM格式存储。
 */
openssl_x509_export($sscert, $certout);
// 查看生成的server.crt的内容
// openssl x509 -in server.crt -text -noout
file_put_contents("server.crt", $certout);
// 也可以使用 openssl_x509_export_to_file(mixed $x509 , string $outfilename)代替
//openssl_x509_export_to_file($sscert, "haha.cert");

