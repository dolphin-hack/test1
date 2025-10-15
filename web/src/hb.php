<?php
require_once "./libs.php";
$user = stateUser();


if($user){
    $h=getallheaders();
    if(intval($h["X-HeartBeat"])>1){
        print "Servertime:".time();
    } else {
        http_response_code(400);
        print "Error";
    }
} else {
    http_response_code(403);
    print "Error";
}
