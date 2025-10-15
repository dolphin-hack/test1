<?php
require_once "./libs.php";
$lang = include "lang_ja.php";
$selected = "register";

session_start();
function errexit($title){
$lang = include "lang_ja.php";
?>
<html>
  <head>
    <title><?php echo $lang["register_err001"]; ?></title>
  </head>
  <body>
    <h1><?php echo $lang["register_err001"]; ?>：<?php print h($title); ?></h1>
    <a href="register.php"><?php echo $lang["register_back"]; ?></a>
  </body>
</html>
<?php
  exit();
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
  $loginid = trim($_POST["loginid"]);
  $password = $_POST["password"];

  // IDに指定文字以外が含まれていた場合はエラーを返す
  $symbols = '-_.@';
  $cls = preg_quote($symbols, '/');
  $pattern = '/[^A-Za-z0-9' . $cls . ']/';
  if (preg_match($pattern, $loginid)){
    errexit($lang["register_err002"]);
  }

  // ログインIDが文字数制限に合致するか確認
  if( !(strlen($loginid) >= 4 && strlen($loginid) <= 20) ){
    errexit($lang["register_err003"]);
  }

  // パスワードが空文字でないか確認
  if( !(strlen($password) >= 1) ){
    errexit($lang["register_err004"]);
  }

  // ニックネームが空文字でないか確認
  if( !(strlen($password) >= 1) ){
    errexit($lang["register_err007"]);
  }

  $cardno = "1234-1234-1234-1234";
  if( !( preg_match("/^\d{4}-\d{4}-\d{4}-\d{4}$/",$cardno) ) ){
    errexit($lang["register_err005"]);
  }

  $uid = User::make($loginid, $password, $_POST["name"], $cardno);
  if($uid){
    session_regenerate_id(true);

    $_SESSION["user_id"] = $uid->id;
    $_SESSION["csrf_token"] = sha1(time() . $uid->id);
    header("Location: ./index.php");
    exit();
  } else {
    errexit($lang["register_err006"]);
  }
}

  printHeader("Register");
?>
    <div class="hero-foot">
      <nav class="tabs is-boxed">
          <div class="container">
          <ul>
          <?php include("./links.php"); ?>
          </ul>
        </div>
      </nav>
      </div>
  </section>
  <div class="container is-max-tablet">
  <form class="box" method="POST">
    <p class="title is-3 "><?php echo $lang["register_register"]; ?></p>

    <div class="field">
      <label class="label"><?php echo $lang["register_loginid"]; ?></label>
      <div class="control">
        <input class="input" type="text" name="loginid" placeholder="LoginID" minlength="4" required>
      </div>
    </div>

    <div class="field">
      <label class="label"><?php echo $lang["register_password"]; ?></label>
      <div class="control">
        <input class="input" type="password" name="password" placeholder="Password" required>
      </div>
    </div>
    
    <div class="field">
      <label class="label"><?php echo $lang["register_name"]; ?></label>
      <div class="control">
        <input class="input" type="text" name="name" placeholder="Mitsui Taro" required>
      </div>
    </div>
    
    <input type="submit" class="button is-info" value="<?php echo $lang["register_submit"]; ?>">
</form>
</div>

<?php
    printFooter();

