<?php
require_once "./libs.php";
$lang = include "lang_ja.php";
$selected = "login";
if(isset($_POST["loginid"])){
  $user = User::login($_POST["loginid"],$_POST["password"]);
  if($user){
    session_start();
    session_regenerate_id(true);
    $_SESSION["user_id"] = $user->id;
    $_SESSION["csrf_token"] = sha1(time() . $user->id);
    header("Location: ./index.php");
    exit();
  }
}
printHeader("Login");
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

    <?php if(isset($_POST["loginid"])){
      printError($lang["login_error001"]);
    } ?>
    <?php if(isset($_GET["text"])){echo '<div class="container is-max-tablet"><div class="notification is-danger is-light"><strong>' . $_GET["text"] . '</strong></div></div>';} ?>

    <div class="container is-max-tablet">
      <form class="box" method="POST">
        <p class="title is-3 "><?php echo $lang["login_login"]; ?></p>
        <div class="field">
          <label class="label"><?php echo $lang["login_loginid"]; ?></label>
          <div class="control">
            <input class="input" type="text" name="loginid" placeholder="LoginID" minlength="4" required/>
          </div>
        </div>

        <div class="field">
          <label class="label"><?php echo $lang["login_password"]; ?></label>
          <div class="control">
            <input class="input" type="password" name="password" placeholder="Password" required />
          </div>
        </div>

        <input type="submit" class="button is-info" value="<?php echo $lang["login_submit"]; ?>" />
      </form>
    </div>
<?php
printFooter();

