<?php
// refs-1: http://en.wikipedia.org/wiki/HMAC
// refs-2: http://www.ietf.org/rfc/rfc2104.txt
function hmac_md5($data, $key) {
    $b = 64; // 每个分组中的字节数, md5是64
    if (strlen($key) > $b) {
        $key = pack("H*", hash('md5', $key));
    }
    $key    = str_pad($key, $b, chr(0x00));
    $ipad   = str_pad('',   $b, chr(0x36));
    $opad   = str_pad('',   $b, chr(0x5c));
    $k_ipad = $key ^ $ipad ;
    $k_opad = $key ^ $opad;

    return hash('md5', $k_opad . pack("H*", hash('md5', $k_ipad . $data)));
}

$a = hmac_md5('applied cryptology', 'hello');
$b = hash_hmac('md5', 'applied cryptology', 'hello'); // http://cn2.php.net/manual/zh/function.hash-hmac.php

if($a === $b) {
    echo "we did HMAC-MD5 right\n";
} else {
    echo 'oops ...';
}

