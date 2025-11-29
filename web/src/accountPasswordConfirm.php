<?php
require_once "./libs.php";
$lang = include "lang_ja.php";
$selected = "account_setting";
$user = stateUser();
if(!$user){
  header("Location: ./login.php");
  exit();
}

printHeader($lang["account_setting"]);

$btn_disable = false;
$errors = [
    'password' => ''
];

// 入力値の取得（送信がなければ空文字）
$password = $_REQUEST['password'] ?? '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    $csrf_token = $_SESSION['csrf_token'];

    if (mb_strlen($password) < 4 || mb_strlen($password) > 20) {
        $errors['password'] = $lang["account_changepass_err001"];
    }

    // エラーがあればボタン無効
    if (array_filter($errors)) {
        $btn_disable = true;
    }
}

include("./links.php");
?>

        </ul>
      </div>
    </nav>
  </div>
</section>

<?php
// if(isset($to_user_id) && tokencheck()){
//   $to_user = User::find_one($to_user_id);
// ?>

  <!-- <div class="section is-medium has-text-centered container"> -->
  <div class="section is-medium has-text-left container">


  <div class="container is-max-tablet">
  <!-- <form class="box" method="POST" action="./accountInfoConfirm.php" > -->
    <form class="box" method="POST"  action="./accountPasswordComplete.php">
    <p ><?php echo $lang["account_changepass_msg001"]; ?></p>

    <div class="field">
    <label class="label"><?php echo $lang["account_password"]; ?>:</label> <p><?php echo $errors['password'] ?></p>
    <div class="control" style="margin-top:1rem;margin-bottom:1rem">
      <input class="input" type="text" name="loginid" value="セキュリティのため非表示" readonly>  
    </div>
    <input type="hidden" name="password" value=<?php echo htmlspecialchars($password, ENT_QUOTES, 'UTF-8'); ?> />
    <!--<input type="password" id="password" name="password" value="<?php echo htmlspecialchars($password, ENT_QUOTES, 'UTF-8'); ?>">

    <input type="checkbox" id="togglePassword">
    <label for="togglePassword"><?php echo $lang["account_togglepass"]; ?></label>-->

    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
    <input type="submit" class="button is-info" value="<?php echo $lang["account_changepass_apply"]; ?>" <?= $btn_disable ? 'disabled' : '' ?>>
    <input type="button" class="button is-info" value="<?php echo $lang["account_changepass_back"]; ?>" onclick="history.back();">
    </div>
  </form>

    <script>
    const pw = document.getElementById('password');
    const toggle = document.getElementById('togglePassword');
    toggle.addEventListener('change', function() {
      pw.type = this.checked ? 'text' : 'password';
    });
  </script>



    <!-- <form class="box" method="POST">

    <div class="field">
      <label class="label">Password</label>
      <div class="control">
        <input class="input" type="password" name="password" placeholder="Password">
      </div>
    </div>
    
    
    <input type="submit" class="button is-info" value="Update">
  </form> -->

</div>
</div>


<?php
printFooter();


