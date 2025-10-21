<?php
ini_set( 'display_errors', 0 );
#error_reporting(E_ERROR | E_WARNING | E_PARSE);

header("X-XSS-Protection: 0");

require_once (__DIR__ . "/lib/idiorm.php");
require_once (__DIR__ . "/lib/paris.php");


spl_autoload_register(function ($class) {
    $filepath = __DIR__ . "/class/" . $class . ".php";
    if(file_exists($filepath)){
        include_once ($filepath);
    }
});



#--------------------------#

//Idiorm & Paris Config
define("DATA_IMAGEDIR", "/data/img");

// ここをMySQLにかえる
//ORM::configure("mysql:/data/market_sqlite3.db");
ORM::configure('mysql:host=mysql;dbname=ecsite');
ORM::configure('username', 'root');
ORM::configure('password', 'password');
ORM::configure('driver_options', [
    PDO::MYSQL_ATTR_INIT_COMMAND       => 'SET NAMES utf8',
    PDO::ATTR_EMULATE_PREPARES         => false,
    PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
]);

function h($str, $flags = ENT_COMPAT, $charset = "UTF-8") {
    return htmlspecialchars($str, $flags, $charset);
}
function hp($str){
    print h($str);
}

function tokencheck(){
     return $_POST["csrf_token"] == $_SESSION["csrf_token"];
}

function stateUser(){
    if(!isset($_SESSION)){
      session_start();
    }
    if(!isset($_SESSION["user_id"])){
      return null;
    }else {
      $user = User::find_one($_SESSION["user_id"]);
      if(!isset($user)){
        return null;
      }
    }
    return $user;
}

function execCurl($url, $name, $filepath) {
  $params= [
    "filename"=> $name,
    "data"=> base64_encode(file_get_contents($filepath))
  
  ];

  $jparams = json_encode($params);

  $headers = array(
    'Content-Type: application/json',
    'Accept-Charset: UTF-8'

  );

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_POST, TRUE);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $jparams);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  $result = curl_exec($ch);
  curl_close($ch);

  if ( $result === false ) {
    $result = [ 'status'=>'500' ];

  }

  return $result;

}

function AdminLink($selected) {
  $state = ($selected === 'admin') ? 'class="is-active"' : "";
  $u = stateUser();
  if($u->priv === 1){
    $_isadmin = '<li ' . $state . '><a href="./____specific_administrative_functions.php" class="pure-menu-link">Admin</a></li>';
  }else{
    $_isadmin = '<!-- <li><a href="./____specific_administrative_functions.php" class="pure-menu-link">Admin</a></li> -->';
  }

  return $_isadmin;
}

function printHeader($name=null){
  $title = "MBSDMARKET " .($name?("-".htmlspecialchars($name)):"");
  $lang = include "lang_ja.php";
  if(stateUser()){
    $u = stateUser();
    $mypoint = h($u->point);

  echo <<<EOM
<!doctype html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="./static/bulma/css/bulma.min.css">
    <script type="text/javascript" src="./static/market/jq.js"></script>
    <script type="text/javascript" src="./static/market/common.js"></script>
    <title>{$title}</title>
  </head>
  <body>
<section class="hero is-dark is-small">
  <div class="hero-head">
    <nav class="navbar">
      <div class="container">
      </div>
    </nav>
  </div>

  <div class="hero-body">
    <div class="container is-flex">
      <a href="./index.php" class="is-flex-grow-1">
      <h1 class="title"> MBSD Market </h1>
      </a>
      <div class="heading has-text-text-65 has-text-centered">{$lang["lib_amount"]}<p class="subtitle is-6">{$mypoint}</p></div>
      <a href="./logout.php?to=%2flogin.php" class="navbar-item" role="button">{$lang["logout"]}</a>
    </div>
  </div>
  <div class="hero-foot">
    <nav class="tabs is-boxed is-fullwidth">
    <div class="container">
      <ul>
EOM;
  ?>
  <?php

}else{
  echo <<<EOM
<!doctype html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="./static/bulma/css/bulma.min.css">
    <title>{$title}</title>
  </head>
  <body>
  <section class="hero is-dark is-small">
    <div class="hero-head">
        <nav class="navbar">
            <div class="container">
            </div>
        </nav>
    </div>


    <div class="hero-body">
      <div class="container">
        <h1 class="title"> MBSD Market </h1>
        <h2 class="subtitle is-5"> Welcome to MBSD Market! </h2>
      </div>
    </div>

EOM;
}
}

function printFooter(){
echo <<<EOM

      </div>
      <div class="pure-u-1-24"></div>
    </div>
    </div>
  </body>
</html>
EOM;
}

function printError($msg, $class = "is-danger") {
  echo("<div class='section'><div class='notification $class'><p class=''>$msg</p></div></div>");
}
?>