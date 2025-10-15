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
    'loginid' => '',
    'name'    => '',
    'cardno'  => ''
];

// 入力の取得（POST送信がなければ空文字に）
$loginid = $_POST['loginid'] ?? '';
$name    = $_POST['name'] ?? '';
$cardno  = $_POST['cardno'] ?? '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // loginidバリデーション
    if (mb_strlen($loginid) < 4 || mb_strlen($loginid) > 20) {
        $errors['loginid'] = $lang["account_changesetting_err001"];
    }

    // nameバリデーション
    if (mb_strlen($name) < 4 || mb_strlen($name) > 20) {
        $errors['name'] = $lang["account_changesetting_err002"];
    }

    // カード番号バリデーション（必要に応じて有効化）
    /*
    if (!preg_match('/^\d{4}-\d{4}-\d{4}-\d{4}$/', $cardno)) {
        $errors['cardno'] = 'クレジットカード番号は正しい形式で入力してください。';
    }
    */

    // 1つでもエラーがあればボタン無効
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
    <form class="box" method="POST"  action="./accountInfoComplete.php">
    <p ><?php echo $lang["account_changesetting_msg001"]; ?></p>

    

    <div class="field">
      <label class="label"><?php echo $lang["account_loginid"]; ?></label> <p><?php echo $errors['loginid'] ?></p>
      <div class="control">
        <!-- <input class="input" type="text" name="loginid" placeholder="LoginID"> -->
        <input class="input" type="text" name="loginid" value="<?php  echo htmlspecialchars($loginid, ENT_QUOTES, 'UTF-8'); ?>" readonly >
      </div>
    </div>
    
    <div class="field">
      <label class="label"><?php echo $lang["account_name"]; ?></label> <p><?php echo $errors['name'] ?></p>
      <div class="control">
        <!-- <input class="input" type="text" name="name" placeholder="Mitsui Taro"> -->
         <input class="input" type="text" name="name" value="<?php  echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>" readonly >
      </div>
    </div>

    <!-- <div class="field">
      <label class="label">カード番号</label>
      <div class="control">
        <input class="input" type="text" name="name" placeholder="12345678"> 
         <p>
        
        </p>
      </div>
    </div> -->
    <input type="hidden" name="priv" value="0">
    <input type="submit" class="button is-info" value="<?php echo $lang["account_changesetting_apply"]; ?>" <?= $btn_disable ? 'disabled' : '' ?>>
    <input type="button" class="button is-info" value="<?php echo $lang["account_changesetting_back"]; ?>" onclick="history.back();">
  </form>

</div>
</div>

<?php
printFooter();


