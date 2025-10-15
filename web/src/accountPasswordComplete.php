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

$errors = [];
$result_msg = '';
$password = $_POST['password'] ?? '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // パスワードバリデーション
    if (mb_strlen($password) < 4 || mb_strlen($password) > 20) {
        $errors['password'] = $lang["account_changepass_err001"];
    }

    if (empty($errors)) {
        $ret = User::accountPasswordUpdate($user, $password);
        if ($ret) {
            $result_msg = $lang["account_changepass_msg002"];
        } else {
            $result_msg = $lang["account_changepass_err002"];
        }
    } else {
        $result_msg = $lang["account_changepass_err003"] . "<br>";
        // 複数エラーに対応
        foreach ($errors as $error) {
            $result_msg .= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') . "<br>";
        }
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
  <form class="box" method="GET" action="./account.php">
     <p ><?php echo $result_msg; ?></p>
      <input type="submit" class="button is-info" value="<?php echo $lang["account_changepass_link"]; ?>">
    </form>
  </form>


</div>
</div>

<?php
printFooter();


