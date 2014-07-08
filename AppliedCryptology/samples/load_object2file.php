<?php
/**
 * @description
 * @author huangwei
 */
$file      = "user.db";
$user_json = @file_get_contents($file);
if($user_json === FALSE) {
    echo "[ERR] $file load failed!\n";
    exit(1);
}
$user_obj = json_decode($user_json);
if($user_obj === NULL) {
    echo "[ERR] $file parse failed!\n";
} else {
    $usr = isset($argv[1]) ? $argv[1] : "";
    $psw = isset($argv[2]) ? $argv[2] : "";
    if(isset($user_obj->$usr) && $user_obj->$usr == $psw) {
        echo "$usr and $psw match!\n";
    } else {
        echo "[ERR] $usr and $psw mismatch!\n";
    }
}
