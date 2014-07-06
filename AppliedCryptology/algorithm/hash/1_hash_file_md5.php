<?php

if(!function_exists("hex2bin")) {
    function hex2bin($str) {
        return pack('H*', $str);
    }
}

$file = isset($argv[1]) ? $argv[1] : "";
if(isset($file) && is_file($file)) {
    echo hash_file('md5', $file), PHP_EOL;
} else {
    echo "$file not exist\n";
}
