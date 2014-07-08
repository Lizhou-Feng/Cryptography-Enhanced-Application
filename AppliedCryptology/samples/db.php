<?php
/**
 * @description
 * @author huangwei
 */

const DEFAULT_DB = "user.db";

/**
 * @description 加载数据库文件，解析文件内容为PHP标准对象
 *
 * @param $file 数据库文件路径，默认值：user.db
 *
 * @return object { $key1 => $value1, $key2 => $value2 }，错误时返回NULL
 */
function load_db($file = DEFAULT_DB) {
    $user_json = @file_get_contents($file);
    if($user_json === FALSE) {
        echo "[ERR] $file load failed!\n";
        return NULL;
    }
    return json_decode($user_json, TRUE);
}

/**
 * @description 向数据库中插入一条用户名和密码记录
 *
 * @param $usr
 * @param $psw
 * @param $db_obj
 * @param $file
 *
 * @return bool This function returns the number of bytes that were written to the file, or FALSE on failure.
 */
function insert($usr, $psw, $db_obj, $file = DEFAULT_DB) {
    if(isset($db_obj) && isset($usr) && isset($psw)) {
        $orig_count = count($db_obj);
        if(!isset($db_obj[$usr])) { // 保证数据库中$usr的唯一性
            $db_obj[$usr] = $psw;
            $new_count = count($db_obj);
            if($new_count - $orig_count === 1) {
                $file_content = json_encode($db_obj);
                return @file_put_contents($file, $file_content);
            }
        }
    }

    return FALSE;
}

/*
 * @description 查询数据库中指定用户名的密码
 *
 * @param $usr
 * @param $file
 * @param $db_obj
 *
 * @return string 密码
 */
function query($usr, $db_obj) {
    if(isset($db_obj)) {
        return isset($db_obj[$usr]) ? $db_obj[$usr] : NULL;
    }
}

