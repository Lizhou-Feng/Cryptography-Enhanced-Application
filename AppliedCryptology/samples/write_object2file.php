<?php
/**
 * @description
 * @author huangwei
 */

$user_obj = array(
    "zhangsan" => "1234",
    "lisi" => "123456",
    "wangwu" => "123",
    "zhaoliu" => "1234abc"
);
$file = "user.db";

$file_content = json_encode($user_obj);
file_put_contents($file, $file_content);

