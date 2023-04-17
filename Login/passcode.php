<?php
function getCode(){
    $alphabet = array("a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z");
    $a = $alphabet[rand() % 26] . $alphabet[rand() % 26];
    $b = rand() % 10;
    $c = $alphabet[rand() % 26];
    $d = (rand() % (100 - 11)) + 11;
    $code = $a . $b . $c . $d;
    return $code;
}
?>