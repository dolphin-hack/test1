<?php
require_once "./libs.php";
$lang = include "lang_ja.php";
$selected = "send_mail";
$user = stateUser();
if(!$user){
  header("Location: ./login.php");
  exit();
}

printHeader($lang["sendmail_sendmail"]);

$to_user_id = @$_POST["to_id"];

include("./links.php");
?>

        </ul>
      </div>
    </nav>
  </div>
</section>

<?php
if(isset($to_user_id) && tokencheck()){
  $to_user = User::find_one($to_user_id);
?>

  <div class="section is-medium has-text-centered container">

<?php
  if( empty($_POST["title"] )|| empty($_POST["message"] ) ){
    $msg = $lang["sendmail_msg_err001"];
    $link = "sendmail.php";
  } else {
    $transaction = $user->sendMessage($to_user,$_POST["title"],$_POST["message"]);
    if($transaction){
      $msg = "{$to_user->name} {$lang["sendmail_msg_send"]}";
    } else {
      $msg = $lang["sendmail_msg_err002"];
    }
    $link = "index.php";
  }
  ?>
  <h1 class="title"><?php echo h($msg) ?></h1>
  <a href="./<?php echo $link ?>" class="pure-button"><?php echo $lang["sendmail_back"]; ?></a>
  </div>
<?php
} else {
?>

<div class="section">
  <div class="block box">

      <div class="section container">
        <p class="title is-3"><?php echo $lang["sendmail_sendmail"]; ?></p>
      </div>

    <form method="POST">
      <input type="hidden" name="csrf_token" value="<?php print $_SESSION["csrf_token"]; ?>">  
      <div class="field is-horizontal">
        <div class="field-label">
          <label class="label"><?php echo $lang["sendmail_selectuser"]; ?></label>
        </div>
        <div class="field-body">
          <div class="field">
            <div class="control">
              <?php
                $lst = $user->getSougoFollows();
                if (empty($lst)){
                  print $lang["sendmail_nomutualfollowers"] . "\n";
                } else {
              ?>

              <div class="select">
                <select name="to_id">
                  <?php
                  foreach($lst as $tmp){
                    print '<option value="' . h($tmp->id) .'">' . h($tmp->name) . '</option>' . "\n";
                  }
                  ?>
                </select>
              </div>
              <?php
                }
              ?>
            </div>
          </div>
        </div>
      </div>

      <div  class="field is-horizontal">
        <div class="field-label">
          <label class="label">
            <?php echo $lang["sendmail_title"]; ?>
          </label>
        </div>
        <div class="field-body">
          <div class="field is-normal">
            <div class="control">
              <input class="input" type="text" class="pure-input-1-3" name="title" placeholder="" required>
            </div>
          </div>
        </div>
      </div>

      <div class="field is-horizontal">
        <div class="field-label">
          <label class="label">
            <?php echo $lang["sendmail_body"]; ?>
          </label>
        </div>
        <div class="field-body">
          <div class="field is-normal">
            <div class="control">
              <textarea class="textarea" name="message" placeholder="" required></textarea>
            </div>
          </div>
        </div>
      </div>
      <div class="field is-horizontal">
        <div class="field-label is-label"></div>
        <div class="field-body">
          <div class="field is-normal">
            <div class="control">
              <input class="button is-info" type="submit" value="<?php echo $lang["sendmail_submit"]; ?>"/>
            </div>
          </div>
        </div>
      </div>
      <input type="hidden" name="csrf_token" value="<?php echo $_SESSION["csrf_token"]; ?>">
    </form>
  </div>
</div>
<script>
  document.getElementBy
<?php
}
printFooter();


