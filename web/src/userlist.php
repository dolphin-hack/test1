<?php
require_once "./libs.php";
$lang = include "lang_ja.php";
$selected = "user_list";
$user = stateUser();

if(!$user){
  header("Location: ./login.php");
  exit();
}

$pdo = User::get_db();
$req_offset = isset($_REQUEST["offset"])?$_REQUEST["offset"]:0;
$maxcount = 0;
$users = array();
$offset = $req_offset*10;

try {
if(isset($_REQUEST["uname"]) && ! empty($_REQUEST["uname"])){
  if(!preg_match("/^[a-zA-Z0-9]{0,}$/",$_REQUEST["uname"])){
    printError("ERROR: " . $lang["userlist_usernameerr001"]);
  }
#  $quoted = User::get_db()->quote("%".$_GET["uname"]."%");
  $quoted = "'%".$_REQUEST["uname"]."%'";
  $users = User::raw_query("SELECT * FROM users WHERE name LIKE ".$quoted." AND loginid != 'master' LIMIT 10 OFFSET $offset")->find_many();
  $st = $pdo->prepare("SELECT COUNT(id) FROM users WHERE name LIKE ? AND loginid != 'master'");

  if($st->execute(["%".$_REQUEST["uname"]."%"])){
    $maxcount = $st->fetch()[0];
  }


} else {
  $users = User::raw_query("SELECT * FROM users WHERE loginid != 'master' LIMIT 10 OFFSET $offset")->find_many();
  $st = $pdo->prepare("SELECT COUNT(id) FROM users WHERE loginid != 'master'");
  if($st->execute()){
    $maxcount = $st->fetch()[0];
  }
}
} catch(Exception $e){

}

printHeader($lang["userlist_userlist"]);
include("./links.php");
?>

        </ul>
      </div>
    </nav>
  </div>
</section>

        <div class="section">
        <div class="block">
        <span style="color:green"><?php echo $lang["userlist_follow"]; ?></span><br />
        <span style="color:blue"><?php echo $lang["userlist_follower"]; ?></span><br />
        <span style="color:pink"><?php echo $lang["userlist_mutualfollower"]; ?></span><br />
        <span style="color:red"><?php echo $lang["userlist_me"]; ?></span>
        </div>
        <form method="POST">
            <div class="field is-horizontal">
              <div class=" field is-grouped">
                <p class="control is-expanded">
                  <input class="input is-rounded" type="text" name="uname" size="16" maxsize="32" placeholder="aaaabbbbccccdddd" value="<?php if(isset($_REQUEST["uname"])) hp($_REQUEST["uname"]); ?>">
                </p>
                <p>
                  <input class="button is-info is-rounded" type="submit" value="<?php echo $lang["userlist_searchusername"]; ?>">
                </p>
              </div>
            </div>
            <p class="help"><?php echo $lang["userlist_message001"]; ?></p>
          </form>
        

        <div class="block">
        <aside class="menu">
          <br />
          <p class="label"><?php echo $lang["userlist_userlist"]; ?></p>
          <ul class="menu-list">
          

<?php
foreach($users as $tmp){
?>

<li><a href="./user.php?id=<?php print h($tmp->id);?>" class="pure-menu-link">
<?php if($user->isFollow($tmp) && $user->isFollower($tmp)){print "<span style=\"color:pink\">&#9829;</span>";} else { ?>
<?php if($user->isFollow($tmp)) print '<span style="color:green">→</span>'; ?>
<?php if($user->isFollower($tmp)) print '<span style="color:blue">←</span>'; ?>
<?php } ?>

<?php if($user->id == $tmp->id) print '<span style="color:red">●</span>'; ?>
<?php print h($tmp->name) ?></a></li>
<?php
}
?>
        </ul>
        </div></aside>
      <nav class="pagination is-rounded" role="navigation" aria-label="pagination">
        <form method="POST">
        <input type="hidden" name="uname" value="<?php if(isset($_REQUEST["uname"])) hp($_REQUEST["uname"]); ?>">
 
<?php
for($i=0;$i*10<$maxcount;++$i) {
    if($req_offset==$i){
        print '<a  class="pagination-link" aria-label="Goto page ' . ($i+1) . '" href="#">'.($i+1)."</a>";
    }else{
        print '<button name="offset" value="'.$i.'"  class="pagination-link" aria-label="Goto page '. ($i+1) .'">'.($i+1).'</button>';
    }
}
print "        </form></nav>";
printFooter();

