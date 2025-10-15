<?php
require_once "./libs.php";
$lang = include "lang_ja.php";
$selected = "user_list";
$user = stateUser();
if(!$user){
  header("Location: ./login.php");
  exit();
}
$to_user = false;
$id = $_GET["id"];
$to_id = @$_POST["to_id"];
$type = @$_POST["type"];

printHeader();

include("./links.php");
?>

        </ul>
      </div>
    </nav>
  </div>
</section>

<div class="section">
<?php
if($_SERVER["REQUEST_METHOD"] != "POST" || !tokencheck()){
    $to_user = User::raw_query("SELECT * FROM users WHERE id={$id}")->find_one();
} else {
    $from_id = $_POST["from_id"];
    $to_user = User::find_one($to_id);
    $from_user = User::find_one($from_id);
    print '<article class="message is-info">';
    if($to_user && $to_user->id != $from_id){
      if($type == 1 && ((int)$to_id != $from_id) && !$from_user->isFollow($to_user)){
      $from_user->follow(User::find_one($to_id));
      echo '  <div class="message-body">'.h($to_user->name) . $lang["user_msg_follow"] . '</div>';
    } elseif($type == 0 && ((int)$to_id != $from_id) && $from_user->isFollow($to_user)) {
      $from_user->unfollow(User::find_one($to_id));
      echo '  <div class="message-body">'.h($to_user->name) . $lang["user_msg_unfollow"] . '</div>';
    }
    print '</article>';
  }
}

?>

<div class="block">
  <p class="label"><?php echo $lang["user_name"]; ?> : <div class="title is-4"><?php echo h($to_user->name); ?> </div></p>
  <?php if ($to_user && $to_user->id == $user->id) {echo '<p class="label">' . $lang["user_amount"] . ' : <div class="title is-5">' . $to_user->point .' </div></p>';} ?>
  <?php flush(); ?>
<?php if($to_user && $user->id != $to_user->id){ ?>
<?php if($user->isSougoFollow($to_user)){ ?>
  <div class="block">
    <form method="POST">
      <input type="hidden" name="type" value="0">
      <input type="hidden" name="to_id" value="<?php print h($to_user->id); ?>">
      <input type="hidden" name="from_id" value="<?php print h($user->id); ?>">
      <input type="hidden" name="csrf_token" value="<?php echo $_SESSION["csrf_token"]; ?>">
      <input type="submit" value="<?php echo $lang["user_unfollow"]; ?>" class="button is-danger">
    </form>
  </div>

  <div class="block">
    <hr width="30%" />
    <form method="POST" action="./sendmail.php" class="pure-form pure-form-stacked ">
      <div class="field">
          <p class="tilte is-2"><?php echo $lang["user_mail_send"]; ?></p>
      </div>
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION["csrf_token"]; ?>">
        <input type="hidden" name="to_id" value="<?php echo h($to_user->id); ?>" />

        <div class="field">
          <label class="label" for="title"><?php echo $lang["user_mail_title"]; ?></label>
          <input class="input" type="text" name="title" id="title" value="" size="20"/>
        </div>

        <div class="field">
          <label class="label" for="message"><?php echo $lang["user_mail_msg"]; ?></label>
          <textarea class="textarea" name="message" id="message" rows="5" cols="50"></textarea>
        </div>

        <input class="button is-info" type="submit" value="Send" class="pure-button-primary pure-button"/>
    </form>
  </div>

<?php } elseif($user->isFollow($to_user)) { ?>

  <div class="block">
    <form method="POST">
      <input type="hidden" name="type" value="0">
      <input type="hidden" name="to_id" value="<?php print h($to_user->id); ?>">
      <input type="hidden" name="from_id" value="<?php print h($user->id); ?>">
      <input type="hidden" name="csrf_token" value="<?php echo $_SESSION["csrf_token"]; ?>">
      <input class="button is-danger" type="submit" value="<?php echo $lang["user_unfollow"]; ?>">
    </form>
  </div>

<?php } else { ?>

  <div class="block">
    <form method="POST">
      <input type="hidden" name="type" value="1">
      <input type="hidden" name="to_id" value="<?php echo h($to_user->get("id")); ?>" />
      <input type="hidden" name="from_id" value="<?php print h($user->id); ?>">
      <input type="hidden" name="csrf_token" value="<?php echo $_SESSION["csrf_token"]; ?>">
      <input class="button is-info" type="submit" value="<?php echo $lang["user_follow"]; ?>" />
    </form>
  </div>

<?php } ?>
<?php } ?>
  <div class="">
    <hr width="30%" />
    <p class="title is-4"><?php echo $lang["user_msg001"]; ?></p>

<?php
  $gpro = Product::where("user_id",$to_user->id)->find_many();
  $products = array();
  foreach($gpro as $p){
    if($p->canSee($user)) $products[] = $p;
  }
?>
<div class="fixed-grid has-3-cols">
  <div class="grid">
    <?php foreach($products as $p) { ?>
    <div class="block">
      <div class="box">
        <div class="cell">
          <a href="./product.php?mode=show&amp;id=<?php hp($p->id); ?>">
            <article class="media">
              <figure class="media-left">
                <p class="image is-128x128">
                  <img src="./img.php?id=<?php hp($p->id); ?>" alt=<?php hp($p->title); ?> style="margin:0 auto;text-align:center;" >
                </p>
              </figure>
              <div class="media-content">
                <div class="media-right">
                  <p class="title is-4"><?php print h($p->title); ?></p>
                  <p class="subtitle is-5"><?php if($p->state != Product::STATE_SELL) print "<s>"; ?><?php print $lang["lib_yen"]; print $p->price; ?><?php if($p->state != Product::STATE_SELL) print "</s>"; ?></p>
                </div>
              </div>
            </article>
          </a>
        </div>
      </div>
    </div>
    <?php } ?>
    </div>
  </div>
</div>
<?php
printFooter();
