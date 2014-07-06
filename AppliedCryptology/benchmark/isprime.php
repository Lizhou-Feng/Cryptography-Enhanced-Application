<?php
$NUM = 111181111;

function is_prime($n){ 
    $i = 2;
    while($i < $n) { 
        if($n % $i == 0) { 
            return false;
        } else {
            $i += 1;
        }
    }
    return true;
}

echo is_prime($NUM);
