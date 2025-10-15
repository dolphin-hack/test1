<?php
require_once "./libs.php";
$lang = include "lang_ja.php";
$selected = "admin";

$user = stateUser();
if(isset($user)){

    if(isset($_POST["mode"]) && $_POST["mode"] == "product"){
      if (move_uploaded_file($_FILES['img']['tmp_name'], DATA_IMAGEDIR."/".$_FILES['img']['name']) ) {
        $pr = Product::find_one(intval($_POST["id"]));
        $pr->img =  $_FILES['img']['name'];
        $pr->save();
        printHeader("img change");
        include("./links.php");
        ?></ul></div></nav></div></section><?php
        print '<div class="section is-medium has-text-centered container"><h1 class="title">Success!</h1></div>';
        printFooter();
      } else {
        printHeader("img change");
        include("./links.php");
        ?></ul></div></nav></div></section><?php
        print '<div class="section is-medium has-text-centered container"><h1 class="title">Error!</h1></div>';
        printFooter();
      }
      exit();
    }elseif(isset($_POST["mode"]) && $_POST["mode"] == "deluser"){
      $puser = User::find_one($_POST["id"]);
      if($puser){
        $puser->delete();  
        printHeader("Delete User");
        include("./links.php");
        ?></ul></div></nav></div></section><?php
        print '<div class="section is-medium has-text-centered container"><h1 class="title">Success!</h1></div>';
        printFooter();
      exit();
      } else {
        printHeader("Delete User");
        printNavBar();
        print '<div class="section is-medium has-text-centered container"><h1 class="title">User ID not exist</h1></div>';  
        printFooter();
        exit();
      }
    } else {
      printHeader("Admin");
      include("./links.php");

?>
        </ul>
      </div>
    </nav>
  </div>
  </section> 
  
  <div class="section">
    <div class="box">
      <h1 class="title is-5"><?php echo $lang["admin_img_msg001"]; ?></h1>
      <form class="pure-form pure-form-stacked" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="mode" value="product" />
        <label class="label"><?php echo $lang["admin_img_id"]; ?>:</label><input class="input" type="number" name="id" />
        <div class="file">
          <label class="file-label">
            <input class="file-input" type="file" name="img" />
            <span class="file-cta">
              <span class="file-label"><?php echo $lang["admin_img_choose"]; ?></span>
            </span>
          </label>
        </div>
        <input class="button" type="submit"/>
      </form>
    </div>

    <div class="box">
      <h1 class="title is-5"><?php echo $lang["admin_userdel_msg001"]; ?></h1>
      <form class="pure-form pure-form-stacked" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="mode" value="deluser" />
        <label class="label"><?php echo $lang["admin_userdel_id"]; ?>:</label><input class="input" type="number" name="id" />
        <input class="button" type="submit"/>
      </form>
    </div>
    
    <div class="box">
      <aside class="menu">
        <p class="menu-label"><?php echo $lang["admin_coupon_msg001"]; ?></p>
        <ul>
          <li><a href="/____generate_coupon.php"><?php echo $lang["admin_coupon_link001"]; ?></a></li>
          <!--
          <li><a href="/coupon.php">UseCouponCode</a></li>
          -->
        </ul>
      </aside>
    </div>
  </div>

<?php 
    printFooter();
    }
}

