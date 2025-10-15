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
  <form class="box" method="POST" action="./accountInfoConfirm.php">
    <p><?php echo $lang["account_msg001"] ?></p>
    <div class="field">
      <label class="label"><?php echo $lang["account_loginid"] ?></label>
      <div class="control">
        <input class="input" type="text" name="loginid" value="<?php  echo htmlspecialchars($user->loginid, ENT_QUOTES, 'UTF-8'); ?>">  
      </div>
    </div>
    
    <div class="field">
      <label class="label"><?php echo $lang["account_name"] ?></label>
      <div class="control">
         <input class="input" type="text" name="name" value="<?php  echo htmlspecialchars($user->name, ENT_QUOTES, 'UTF-8'); ?>">        
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
    
    <input type="submit" class="button is-info" value="<?php echo $lang["account_apply"] ?>">
  </form>

  <form class="box" method="POST" action="./accountPasswordConfirm.php">
    <p ><?php echo $lang["account_changepass"] ?></p>
    <div class="field">
      <label class="label"><?php echo $lang["account_password"] ?></label>
      <div class="control">
        <input class="input" id="password" type="password" name="password" placeholder="Password">
      </div>
     <input type="checkbox" id="togglePassword">
    <label for="togglePassword"><?php echo $lang["account_togglepass"] ?></label>

    </div>
    
    
    <input type="submit" class="button is-info" value="<?php echo $lang["account_apply"] ?>">
  </form>

    <script>
    const pw = document.getElementById('password');
    const toggle = document.getElementById('togglePassword');
    toggle.addEventListener('change', function() {
      pw.type = this.checked ? 'text' : 'password';
    });
  </script>

</div>
</div>



<?php
printFooter();


