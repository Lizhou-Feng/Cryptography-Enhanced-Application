<?php
$algorithms = mcrypt_list_algorithms();

printf("%-18s %s %8s\n", "算法名", "最大支持密钥长度（字节）", "分组(block)/流式(stream)");
foreach ($algorithms as $cipher) {
    $max_key_size = mcrypt_module_get_algo_key_size($cipher);
    $stream_mode  = mcrypt_module_is_block_algorithm($cipher) == TRUE ? "block" : "stream";
    printf("%-18s %3d %25s\n", $cipher, $max_key_size, $stream_mode);
}
