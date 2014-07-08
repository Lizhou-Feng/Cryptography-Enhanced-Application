<?php
/**
 * @description
 * @author huangwei
 */

require 'db.php';

$db_file = isset($argv[1]) ? $argv[1] : "";
$usr     = isset($argv[2]) ? $argv[2] : "";
$psw     = isset($argv[3]) ? $argv[3] : "";

if(!is_file($db_file)) { // db文件不存在，首次插入数据
    insert($usr, $psw, array(), $db_file);
} else {
    $user_obj = load_db();
    if($user_obj === NULL) {
        echo "[ERR] $file parse failed!\n";
    } else {
        if(query($usr, $user_obj) === NULL) {
            if(insert($usr, $psw, $user_obj, $db_file) === FALSE) {
                echo "[ERR] insert $usr into $db_file faild!\n";
            } else {
                echo "insert $usr into $db_file succeed!\n";
            }
        } else {
            echo "[WARN] duplilcated $usr, skip insert!\n";
        }
    }
}
