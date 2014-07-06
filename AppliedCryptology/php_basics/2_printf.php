<?php

$dec_array = array(8, 256, 111);

printf("%-4s %12s %4s\n", "dec", "bin", "hex");
foreach($dec_array as $dec) {
    $bin = sprintf("%b", $dec);
    $hex = sprintf("%x", $dec);
    printf("%-4s %12s %4s\n", $dec, $bin, $hex);
}

