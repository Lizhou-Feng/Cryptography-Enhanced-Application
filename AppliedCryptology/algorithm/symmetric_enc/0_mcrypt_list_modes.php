<?php
$modes = mcrypt_list_modes();

foreach ($modes as $mode) {
    echo "$mode \n";
}
