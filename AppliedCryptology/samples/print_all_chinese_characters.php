<?php
/**
 * @description
 * @author huangwei
 */

// ref: http://stackoverflow.com/questions/1366068/whats-the-complete-range-for-chinese-characters-in-unicode
$all = array("4E00-9FFF", "3400-4DFF", "020000-02A6DF", "F900-FAFF", "02F800-02FA1F");
//$all = array("4E00-9FFF");

foreach($all as $r) {
    $range = preg_split('/-/', $r);
    $start = base_convert($range[0], 16, 10);
    $end   = base_convert($range[1], 16, 10);
    $c = "";
    for($i = $start;$i <= $end; $i++) {
        $c .= @iconv('UCS-2', 'UTF-8', hex2bin(base_convert($i, 10, 16)));
    }
    file_put_contents('chinese.characters.txt', $c.PHP_EOL, FILE_APPEND);
}
