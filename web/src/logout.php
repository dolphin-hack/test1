<?php
require_once "./libs.php";
$user = stateUser();
session_destroy();
$redirect_param_name='to';
if (isset($_GET[$redirect_param_name])) {
    $target = $_GET[$redirect_param_name];
    header("Location: {$target}"."?text=%E3%83%AD%E3%82%B0%E3%82%A2%E3%82%A6%E3%83%88%E3%81%97%E3%81%BE%E3%81%97%E3%81%9F");
    exit();
}
if($user){
  header("Location: ./login.php?text=%E3%83%AD%E3%82%B0%E3%82%A2%E3%82%A6%E3%83%88%E3%81%97%E3%81%BE%E3%81%97%E3%81%9F"); #ログアウトしました
  exit();
}

$_SESSION = array();
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
else{
  header("Location: ./login.php");
}
exit();
