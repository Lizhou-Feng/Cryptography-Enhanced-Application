<?php

$data = "hello applied cryptology"; 

foreach (hash_algos() as $v)
{
    $time=microtime(true);
    for ($i = 0; $i < 10000; $i++)
    {
        // 为了避免计算结果被缓存，每次计算Hash值的字符串都在变：$data.$i
        $r[$v] = strlen(hash($v, $data.$i, false));
    }
    $t[$v] = microtime(true)-$time;
    if(!is_numeric($t[$v])) {
        echo $t[$v]."\n";
    }
}


$sort_by = isset($argv[1]) ? $argv[1] : '';

switch ($sort_by)
{
case 'length':
    asort ($r, SORT_NUMERIC);
    $array = 'r';
    break;
case 'time':
    asort ($t, SORT_NUMERIC);
    $array = 't';
    break;
default:
    ksort ($r, SORT_STRING);
    $array = 'r';
    break;
}

foreach ($$array as $key => $value)
{
    printf("%-12s %5d    %f\n", $key, $r[$key], $t[$key]); 
}


