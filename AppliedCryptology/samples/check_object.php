<?php
/**
 * @description
 * @author huangwei
 */

require 'db.php';

$db_file = isset($argv[1]) ? $argv[1] : "";
$usr = isset($argv[2]) ? $argv[2] : "";
$psw = isset($argv[3]) ? $argv[3] : "";

$user_obj = load_db();
if($user_obj === NULL) {
    echo "[ERR] $file parse failed!\n";
} else {
    if(isset($user_obj[$usr]) && $user_obj[$usr] == $psw) {
        echo "$usr and $psw match!\n";
    } else {
        echo "[ERR] $usr and $psw mismatch!\n";
    }
}
