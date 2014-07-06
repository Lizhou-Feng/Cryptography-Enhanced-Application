<?php
$ctx = hash_init('md5');
var_dump($ctx);
hash_update($ctx, '1');
var_dump($ctx);
hash_update($ctx, '2');
var_dump($ctx);
echo hash_final($ctx)."\n";
var_dump($ctx);

/* 上面的几个函数调用和下面这个函数调用完全相同，存在的意义？*/
/* 仅仅是为了作为其他HASH函数实现的基础函数？ */
echo hash('md5', '12')."\n";

/* No! */
/* 看下面这个例子 */
function signParam($key, $data){
    $ctx = hash_init("md5", HASH_HMAC, $key);

    foreach ($data as $key => $value)
    {
        hash_update($ctx, $key);
        hash_update($ctx, $value);   
    }

    return hash_final($ctx);
}

$data = array(
    'param1' => 'value1',
    'param2' => 'value2',
    'param3' => 'value3'
);
$key = '86C30E8A-A24F-41E6-88B2-014516229AE7';
$digest = signParam($key, $data);
echo "signed using HMAC-MD5 on Array: ". $digest . "\n";

