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

$result_msg = '';
$loginid = '';
$name = '';
$priv = '';
$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // バリデーションルール
    $loginid = $_POST["loginid"] ?? '';
    $name    = $_POST["name"] ?? '';
    $priv    = $_POST["priv"] ?? '';

    if (mb_strlen($loginid) < 4 || mb_strlen($loginid) > 20) {
        $errors[] = $lang["account_changesetting_err001"];
    }

    if (mb_strlen($name) < 4 || mb_strlen($name) > 20) {
        $errors[] = $lang["account_changesetting_err002"];
    }

    if (!in_array($priv, ['0', '1'], true)) {
      $errors[] = $lang["account_changesetting_err003"];
    }

    $otherUser = User::where('loginid', $loginid)->where_not_equal('id', $user->id)->find_one();

    if ($otherUser) {
        // 他ユーザーが利用
        $errors[] =  $lang["account_changesetting_err004"];

    }

    if (empty($errors)) {
        
        $ret = User::accountInfoUpdate($user, $loginid, $name, $priv);
        if ($ret) {
            $result_msg = $lang["account_changesetting_msg002"];
        } else {
            $result_msg = $lang["account_changesetting_err005"];
        }
    } else {
        $result_msg = $lang["account_changesetting_msg002"] . "<br>";
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
      <input type="submit" class="button is-info" value="<?php echo $lang["account_changesetting_link"]; ?>">
    </form>
  </form>


  </div>
</div>

<?php
printFooter();


