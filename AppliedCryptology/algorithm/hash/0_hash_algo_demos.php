<?php
// refs: http://www.php.net/manual/zh/function.hash.php
$data = "hello applied cryptology"; 

foreach (hash_algos() as $v) { 
    $r = hash($v, $data, false); 
    printf("%-12s %3d %s\n", $v, strlen($r), $r); 
} 
