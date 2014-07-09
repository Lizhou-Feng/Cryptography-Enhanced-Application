<?php
/**
 * @description
 * @author huangwei
 */

/**
 * @description 汉字转换为Unicode UCS-2格式(\x{XXXX})表示
 *
 * @param string 汉字输入
 *
 * @return string UCS-2格式字符串
 */
function chineseToUnicode($str){
    //split word
    preg_match_all('/./u', $str, $matches);

    $c = "";
    foreach($matches[0] as $m) {
        $c .= "\x{".bin2hex(iconv('UTF-8',"UCS-2",$m))."}";
    }
    return $c;
}

/**
 * @description 检查输入字符是否全中文
 *
 * @param string 字符串输入
 *
 * @return mixed 识别出的汉字字符串数组
 */
function chineseValidator($str) {
// 中文字符集范围定义：http://stackoverflow.com/questions/1366068/whats-the-complete-range-for-chinese-characters-in-unicode
    $pattern1 = '/[\x{4E00}-\x{9FFF}]+/u';
    preg_match_all($pattern1, $str, $matches);

    $ret = array();
    foreach($matches[0] as $m) {
        array_push($ret, $m);
    }

    return $ret;
}


$cjk_array = chineseValidator($argv[1]);
foreach($cjk_array as $cjk) {
    printf("%s: %s\n", $cjk, chineseToUnicode($cjk));
}
