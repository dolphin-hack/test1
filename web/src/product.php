<?php
require_once "./libs.php";
$lang = include "lang_ja.php";
$user = stateUser();
if(!$user){
    header("Location: ./login.php?text=%E3%81%93%E3%81%AE%E6%A9%9F%E8%83%BD%E3%82%92%E4%BD%BF%E3%81%86%E3%81%AB%E3%81%AF%E3%83%AD%E3%82%B0%E3%82%A4%E3%83%B3%E3%81%8C%E5%BF%85%E8%A6%81%E3%81%A7%E3%81%99"); #この機能を使うにはログインが必要です
    exit();
}

function errexit($title){
$lang = include "lang_ja.php";
?>
<html>
  <head>
    <title><?php echo $lang["register_err001"]; ?></title>
  </head>
  <body>
    <h1><?php echo $lang["register_err001"]; ?>：<?php print h($title); ?></h1>
    <a href="register.php"><?php echo $lang["register_back"]; ?></a>
  </body>
</html>
<?php
  exit();
}

$d_mode = isset($_GET["mode"])?$_GET["mode"]:"";

if(isset($_GET["id"])){
    if($d_mode == "show"){
        showProduct($user, $lang);
    } elseif($d_mode == "buy"){
        buyProduct($user, $lang);
    } elseif($d_mode == "make"){
        makeProduct($user, $lang);
    }
} else {
    if($d_mode == "own"){
        ownProducts($user, $lang);
    } else {
        showProducts($user, $lang);
    }
}

#######################################
function showProduct($user, $lang){
    $product = Product::find_one($_GET["id"]);
    if(!$product){print "<h1>Error!!</h1>\n";printFooter();exit();}
    if(!$product->canSee($user)){print "<h1>Error!</h1>\n";printFooter();exit();}
    printHeader($product->title);
    if(isset($_POST["state"]) && $_POST["state"] == "comment_commit"){
        if(empty($_POST["text"])){print "<h1>{$lang['product_showproduct_comment_err001']}</h1>\n";printFooter();exit();}
        $tc = Comment::create();
        $tc->text = $_POST["text"];
        $tc->product_id = $product->id;
        $tc->user_id = $user->id;
        $tc->save();
    }
    $comments = Comment::where("product_id",$product->id)->find_many();

    $selected = "search";
    include("./links.php");
?>

        </ul>
      </div>
    </nav>
  </div>
</section>

    <div class="section container">
    <article class="media">
        <figure class="media-left">
            <p class="image is-128x128" style="border: 1px solid #999" >
                <img src="./img.php?id=<?php hp($product->id); ?>" alt=<?php hp($product->title); ?>>
            </p>
        </figure>

        <div class="media-content">

            <h2 class="title"><?php hp($product->title); ?></h2>

            <table class="table">
                <tbody>
                    <tr>
                        <td><?php echo $lang["product_showproduct_price"]; ?></td>
                        <td><?php hp($product->price); $lang["lib_yen"]; ?></td>
                    </tr>
                    <tr>
                        <td><?php echo $lang["product_showproduct_seller"]; ?></td>
                        <td><a href="./user.php?mode=show&amp;id=<?php hp($product->user_id); ?>"><?php hp($product->user()->name); ?></a></td>
                    </tr>
                    <?php if($user->id == $product->user_id) { ?>
                    <tr>
                        <td><?php echo $lang["product_showproduct_condition"]; ?></td>
                        <td><?php print $product->state==Product::STATE_SELL?$lang["product_showproduct_sell"]:"<span style=\"font-color:red;\">{$lang['product_showproduct_soldout']}</span>"; ?></td>
                    </tr>
                    <tr>
                        <td><?php echo $lang["product_showproduct_edit"]; ?></td>
                        <td> <?php if($product->state==Product::STATE_SELL){ ?>
                            <a href="?mode=make&amp;id=<?php print $product->id;?>"><?php echo $lang["product_showproduct_editproduct"]; ?></a></td>
                            <?php } else { ?>
                            <?php echo $lang["product_showproduct_noteditable"]; ?>
                            <?php } ?>
                    </tr>
                    <?php } else { ?>
                    <tr>
                        <td><?php echo $lang["product_showproduct_condition"]; ?></td>
                        <td><?php print $product->state==Product::STATE_SELL?"<a href=\"?mode=buy&amp;id={$product->id}\">{$lang["product_showproduct_sell"]}</a>":"<span style=\"font-color:red;\">{$lang['product_showproduct_soldout']}</span>"; ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>

        </div>
    </article>

    <h2 class="title is-4"><?php echo $lang["product_showproduct_productoverview"]; ?></h2>
    <div class="content">
        <p><?php hp($product->text); ?></p>
    </div>

    </div>

    <div class="section container">
    <hr size="30%">
    <h2 class="title is-6"><?php echo $lang["product_showproduct_comment"]; ?></h2>
    <?php foreach($comments as $c){ ?>
        <article class="message">
        <div class="message-header">
            <p>
                <?php hp($c->timestamp); ?> : <a href="./user.php?id=<?php hp($c->user_id); ?>"><?php hp($c->user()->name); ?></a>
            </p>
        </div>
        <div class="message-body">
            <p><?php print $c->text; ?></p>
        </div>
        </article>
    <?php } ?>
    <div class="section container">
    <form method="POST" class="" >
        <label class="label"><?php echo $lang["product_showproduct_postcomment"]; ?></label>
        <div class="field">
            <div class="block">
            <div class="control">
                <textarea class="textarea is-rounded" name="text" placeholder="<?php echo $lang["product_showproduct_postplaceholder"]; ?>"></textarea>
            </div>
            </div>
            <div class="block">
            <div class="control">
                <input class="button" type="submit" value="<?php echo $lang["product_showproduct_submit"]; ?>">
                <input type="hidden" name="csrf_token" value="<?php hp($_SESSION["csrf_token"]); ?>">
                <input type="hidden" name="state" value="comment_commit">
            </div>
            </div>
        </div>
    </form>
    </div>
<?php
}

#######################################
function showProducts($user, $lang){
    $products = null;
    if(isset($_GET["title"])){
        $products = array();
        $rawp = Product::raw_query("SELECT * FROM products WHERE title LIKE '%" . $_GET["title"] ."%'")->find_many();
        foreach($rawp as $p){
            if($p->canSee($user)){
                $products[] = $p;
            }
        }
    } else {
        $products = Product::getProductList($user);
    }
    printHeader();
    
    $selected = "search";
    include("./links.php");
?>

        </ul>
      </div>
    </nav>
  </div>
</section>

    <div class="section is-max-tablet ">
        <form>
            <p class="subtitle is-5"></p>
            <div class="field is-horizontal">
            <div class="field is-grouped">
            <p class="control is-expanded">
                <input class="input is-rounded" type="text" name="title" placeholder="<?php echo $lang["product_showproducts_productname"]; ?>" value="<?php if(isset($_GET["title"])) print $_GET["title"]; ?>">
            </p>
            <p class="control">
                <input class="button is-info is-rounded" type="submit" value="<?php echo $lang["product_showproducts_searchproduct"]; ?>">
            </p>
            </div>
            </div>
        </form>
    </div>
    <div class="section">
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
                                        <img src="./img.php?id=<?php hp($p->id); ?>" alt="<?php hp($p->title); ?>" >
                                    </p>
                                </figure>

                                <div class="media-content">
                                <div class="media-right">
                                    <div class="content ">                                        
                                        <p class="title is-4"><?php print h($p->title); ?></h1>
                                        <p class="subtitle is-5"><?php if($p->state != Product::STATE_SELL) print "<s>"; ?><?php print $lang["lib_yen"]; print $p->price; ?><?php if($p->state != Product::STATE_SELL) print "</s>"; ?></p>

                                    </div>
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
    </div>
    
<?php
printFooter();
}

#######################################
function makeProduct($user, $lang){
    $INSPECT_URL = 'http://api.mbsdmarket2025.local:3000/api/inspect';

    $lu = "";
    if($_GET["id"] != "new"){
        $product = Product::find_one($_GET["id"]);
        if($product->user_id != $user->id || $product->state != Product::STATE_SELL){
            header("location: index.php");
            exit();
        }
        $lu = LimitUser::find_one($product->limitUser_id);
    } else {
        $product = Product::create();
    }
    $firends = $user->getFollows();

    if(isset($_POST["form_state"]) && $_POST["form_state"] == "confirm"){
        $filename=sha1(time() . "<scirpt>alert(1)</script>" . $user->id . rand());

        if($_FILES['img']['size'] > 1024 * 1024){
            header("location: ./product.php?error=File+Size+Error&mode=make&id=".($product->id?$product->id:"new"));
            exit();
        }
        $filenew = false;
        $imgkey = "";

        // アップロードファイルのウイルス、ファイル種別チェック
        if ( isset($_POST['url']) && !empty($_POST['url']) ) {
            $INSPECT_URL = $_POST['url'];
        }

        if ( $_FILES['img']['size'] !== 0 ) {
            $result = json_decode(execCurl($INSPECT_URL, $_FILES['img']['name'], $_FILES['img']['tmp_name']), true);
            if ( empty($result) || $result['status'] !== 200 ) {
                header("location: ./product.php?error=Virus+Check+Error&mode=make&id=" . ($product->id?$product->id:"new"));
                exit();
            }
        }

        if (move_uploaded_file($_FILES['img']['tmp_name'], DATA_IMAGEDIR."/".$filename) ) {
            $filenew = true;
            if(!isset($_SESSION["filecount"])) $_SESSION["filecount"] = 0;
            $_SESSION["filecount"] += 1;
            $_SESSION["tmp_filecount_".$_SESSION["filecount"]] = $filename;
            $imgkey = $_SESSION["filecount"];
        }else if(isset($_POST["filecount"])){
            $filenew = true;
            $imgkey = $_POST["filecount"];
        }

        if (!isset($_POST["title"]) || trim($_POST["title"]) === ""){
            errexit($lang["product_showproduct_comment_err002"]);
        }

        if (!isset($_POST["price"]) || trim($_POST["price"] === "")) {
            errexit($lang["product_showproduct_comment_err003"]);
        }
        printHeader("confirm ".$_POST["title"]);
        $_POST["price"] = intval($_POST["price"])>0?intval($_POST["price"]):0;

        $selected = "new_item";
        include("./links.php");
?>
        </ul>
      </div>
    </nav>
  </div>
</section>

<form method="POST" action="./product.php?mode=make&amp;id=<?php hp($_GET["id"]);?>">
    <div class="section container">
    <article class="media">

        <?php if($filenew){ ?>
            <figure class="media-left">
                <p class="image is-128x128">
                    <img src="./img.php?tmp=<?php hp($imgkey); ?>" alt=<?php hp($_POST["title"]);?>></p>
                </p>
                <input type="hidden" name="filecount" value="<?php hp($imgkey); ?>">
            </figure>            
        <?php } ?>


        <div class="media-content">

            <h2 class="title"><?php print $_POST["title"]; ?></h2>
            <input type="hidden" name="title" value="<?php hp($_POST["title"]) ?>">

            <table class="table">
                <tbody>
                    <tr>
                        <td><?php echo $lang["product_makeproduct_price"]; ?></td>
                        <td><?php print $_POST["price"]; ?></td>
                        <input type="hidden" name="price" value="<?php hp($_POST["price"]) ?>">
                    </tr>
                    <tr>
                        <td><?php echo $lang["product_makeproduct_purchaserestriction"]; ?></td>
                        <td><?php if($_POST["type"] == "2"){print $lang["product_makeproduct_ristrictionlist"];}elseif($_POST["type"] == "1"){print $lang["product_makeproduct_mutualfollowers"];}elseif($_POST["type"]=="3"){print $lang["product_makeproduct_onlyfollowers"];}else{print $lang["product_makeproduct_norestriction"];} ?></td>
                        <input type="hidden" name="type" value="<?php hp($_POST["type"]) ?>">
                    </tr>
                    <tr>
                        <td><?php echo $lang["product_makeproduct_eligiblebuyer"]; ?></td>
                        <td>
                        <?php
                        if($_POST["type"] === "2" && isset($_POST["targets"])){
                            for($i=0;$i<count($_POST["targets"]);++$i) {
                                $u = User::find_one($_POST["targets"][$i]);
                                if($user->isFollow($u)){
                                    print h($u->name) . "<br />";
                                    print '<input type="hidden" name="targets[]" value="'.h($u->id).'">';
                                }
                            }
			}
                        ?>
                        </td>
                    </tr>
                </tbody>
            </table>

        </div>
    </article>

    <h2 class="title is-4"><?php echo $lang["product_makeproduct_productoverview"]; ?></h2>
    <div class="content">
        <div class="content block"><p><?php hp($_POST["text"]); ?></p></div>
        <input type="hidden" name="text" value="<?php hp($_POST["text"]); ?>">
    </div>

        <div class="">
            <input type="hidden" name="csrf_token" value="<?php hp($_SESSION["csrf_token"]);?>">
            <button class="button is-info" type="submit" id="submitbutton" name="form_state" value="commit"><?php echo $lang["product_makeproduct_register"]; ?></button>
            <button class="button" type="submit" class="pure-button" name="form_state" value="back"><?php echo $lang["product_makeproduct_back"]; ?></button>
        </div>
        </div>
    </form>

<script>
var ttype = <?php hp($_POST["type"]) ?>;
if(ttype == 2){
    $("#targetusers").show();
}
</script>

<?php
    printFooter();
    } elseif(isset($_POST["form_state"]) && $_POST["form_state"] == "commit"){
        $_POST["price"] = intval($_POST["price"])>0?intval($_POST["price"]):0;
        $product->title = $_POST["title"];
        $product->text = $_POST["text"];
        $product->price = $_POST["price"]>0?$_POST["price"]:0;
        $product->user_id = $user->id;
        $product->state = Product::STATE_SELL;


        if(isset($_SESSION["filecount"]) && isset($_POST["filecount"]) && isset($_SESSION["tmp_filecount_".$_POST["filecount"]])){
            $product->img = $_SESSION["tmp_filecount_".$_POST["filecount"]];
        }
        $product->mode = $_POST["type"];

        if($product->mode == "2"){
            $lu = null;
            if($product->limitUser_id > 0){
                $lu = LimitUser::find_one($product->limitUser_id);
            } else {
                $lu = LimitUser::create();
            }
            $lut = "";
	    if(isset($_POST["targets"]) && count($_POST["targets"]) > 0){
            	for($i=0;$i<count($_POST["targets"]);++$i){
            	    if($user->isFollow(User::find_one($_POST["targets"][$i]))){
            	        $lut .= $_POST["targets"][$i].",";
            	    }
            	}
	    }
            $lut = chop($lut,",");
            $lu->users = $lut;
            $lu->save();
            $product->limitUser_id=$lu->id;
        }
        $product->save();
        printHeader("Make Products");
        //print "<h1>商品登録しました</h1>";

        $selected = "new_item";
        include("./links.php");
    ?>
        </ul>
      </div>
    </nav>
  </div>
</section>

    <div class="section is-medium has-text-centered container">
        <h1 class="title"><?php echo $lang["product_makeproduct_registrationcomplete"]; ?></h1>
    </div>

    <?php printFooter();
    } else {
        printHeader("Make Products");
        $friends = $user->getFollows();
        if(isset($_POST["form_state"]) &&$_POST["form_state"] == "back"){
            $product->title = $_POST["title"];
            $product->text = $_POST["text"];
            $product->price = $_POST["price"];
            $product->mode = $_POST["type"];
        }
        $targets = $_POST["targets"] ?? [];
        //if (isset($limitUsers)) {
        //    foreach ((array)$product->limitUsers() as $u) {
        //        $targets[] = $u->id;
        //    }
        //}
	if(isset($lu->users)){
	    $targets = array_map('intval', explode(',', $lu->users));
	}

        $selected = "new_item";
        include("./links.php");
?>

        </ul>
      </div>
    </nav>
  </div>
</section>

<?php
if( isset($_GET["error"]) ){
    printError( htmlspecialchars( $_GET["error"] ));
}
?>


<form class="pure-form pure-form-stacked" method="POST" action="./product.php?mode=make&amp;id=<?php hp($_GET["id"]);?>"  enctype="multipart/form-data" id="productform">
    <div class="section box">
    <div class="field container">
        <label class="label"><?php echo $lang["product_makeproduct_productname"]; ?></label>
        <div class="control">
            <input class="input" type="text" name="title" value="<?php hp($product->title);?>" placeholder="Title" />
        </div>
    </div>

    <div class="field container">
        <div class="field-body">
            <div class="field">
                <label class="label"><?php echo $lang["product_makeproduct_productimg"]; ?></label>
                <?php if(isset($_POST["filecount"]) ){ ?>
                    <img class="image is-256x256" src="./img.php?tmp=<?php hp($_POST["filecount"]); ?>" alt="tmp">
                    <input type="hidden" name="filecount" value="<?php hp($_POST["filecount"]); ?>">
                <?php } elseif($_GET["id"] != "new") { ?>
                    <img class="image is-256x256" src="./img.php?id=<?php hp($_GET["id"]); ?>" alt="now">
                <?php } ?>
                <div class="file">
                <input id="IMG" type="file" name="img" /> 
                </div>
            </div>
        </div>
    </div>

    <div class="field container">
        <label class="label"><?php echo $lang["product_makeproduct_productoverview"]; ?></label>
        <div class="control">
            <textarea class="textarea" name="text" placeholder="Product Description" ><?php hp($product->text);?></textarea>
        </div>
    </div>

    <div class="field container">
        <label class="label"><?php echo $lang["product_makeproduct_productprice"]; ?></label>
        <input class="input" type="number" name="price" value="<?php hp($product->price);?>" min="0">
    </div>

    <div class="field container">
        <label class="label"><?php echo $lang["product_makeproduct_purchaserestriction"]; ?></label>
        <div class="select">
            <select id="seigen" name="type" onchange='this.value == "2"?$("#target_list").show():$("#target_list").hide();'>
                <option value="0" <?php if($product->mode == Product::MODE_EVERYONE) print "selected";?>>
                    <?php echo $lang["product_makeproduct_norestriction"]; ?>
                </option>
                <option value="1" <?php if($product->mode == Product::MODE_ONLYSOUGOFOLLOWER) print "selected";?>>
                    <?php echo $lang["product_makeproduct_mutualfollowers"]; ?>
                </option>
                <option value="2" <?php if($product->mode == Product::MODE_ONLYLIMITEDUSER) print "selected";?>>
                    <?php echo $lang["product_makeproduct_selectfollower"]; ?>
                </option>
                <option value="3" <?php if($product->mode == Product::MODE_ONLYFOLLOWER) print "selected";?>>
                    <?php echo $lang["product_makeproduct_onlyfollowers"]; ?>
                </option>
            </select>
        </div>
    </div>

    <div class="field container">
        <div class style="display:none" id="target_list">
            <p class="subtitle is-6"><?php echo $lang["product_makeproduct_specifyeligiblebuyer"]; ?></p>
            <?php
            foreach($friends as $f){
                $c = "checked";
                if($product->mode == "2"){
                    if(array_search($f->id,$targets) === FALSE){
                        $c = "";
                    }
                }
            ?>
            <label for="target_cbx_<?php hp($f->id); ?>" class="checkbox" >
                <input id="target_cbx_<?php hp($f->id); ?>" type="checkbox" name="targets[]" value="<?php hp($f->id);?>" <?php hp($c); ?>><?php print $f->name;//見えないけど…… ?>
            </label>
            <?php } ?>
        </div>
    </div>

    <div class="field container">
    <button class="button is-info" id="submitbutton"><?php echo $lang["product_makeproduct_submit"]; ?></button>
    
    <input type="hidden" name="csrf_tokon" value="<?php print $_SESSION["csrf_token"]; ?>">
    <input type="hidden" name="form_state" value="confirm">

    <input type="hidden" name="url" value="http://api.mbsdmarket2025.local:3000/api/inspect">
    </div>

    </div>

</form>
<script>
    window.onload=function(){

        $("#seigen")[0].value == "2"?$("#target_list").show():$("#target_list").hide();
        $("#submitbutton").on("click",function(){
        if($("#IMG").prop("files").length == 0){
            $("#productform").submit();
            return;
        }
        if($("#IMG").prop('files')[0].size < 1024 * 1024){
            $("#productform").submit();
        } else {
            alert("<?php print $lang["product_makeproduct_uploadsizeerr001"]; ?>");
        }
    });}
</script>
<?php
    printFooter();
    }
}

#######################################
function buyProduct($user, $lang){

    $product = Product::find_one($_GET["id"]);
    if(!$product || $product->state != Product::STATE_SELL){print "<h1>Error!!</h1>\n";printFooter();exit();}
    if(!$product->canSee($user) || $user->id == $product->user_id){print "<h1>Error!</h1>\n";printFooter();exit();}
    printHeader($product->title);

    $selected = "search";
    include("./links.php");
?>

        </ul>
      </div>
    </nav>
  </div>
</section>

<?php

    if(isset($_POST["state"]) && $_POST["state"] == "commit"){
        if($_POST["csrf_token"] == $_SESSION["csrf_token"]){
            print('<div class="section is-medium has-text-centered container">');
            if($product->buy($user, intval( $_POST["price"] ))){
                print "<h1 class='title'>{$lang["product_buyProduct_success"]}</h1><a href='./product.php'>{$lang["product_buyProduct_back"]}</a>";
                printFooter();
                exit();
            } else {
                print "<h1 class='title'>{$lang["product_buyProduct_fail"]}</h1><a href='./product.php'>{$lang["product_buyProduct_back"]}</a>";
                printFooter();
                exit();
            }

            print('</div>');
        }
    }
?>

<div class="section container">
    <article class="media">
        <figure class="media-left">
            <p class="image is-256x256">
                <img class="pure-img" src="./img.php?id=<?php hp($product->id); ?>" alt="product">
            </p>
        </figure>

        <div class="media-content">
            <h2 class="title"><?php hp($product->title); ?></h2>

            <table class="table">
            <tbody>
                <tr>
                    <td><?php echo $lang["product_buyProduct_price"]; ?></td>
                    <td><?php hp($product->price); ?></td>
                </tr>
                <tr>
                    <td><?php echo $lang["product_buyProduct_seller"]; ?></td>
                    <td><a href="./user.php?mode=show&amp;id=<?php hp($product->user_id); ?>"><?php hp($product->user()->name); ?></a></td>
                </tr>
                <tr>
                    <td><?php echo $lang["product_buyProduct_state"]; ?></td>
                    <td><?php echo $lang["product_buyProduct_available"]; ?></td>
                </tr>
            </tbody>
        </table>

        </div>
    </article>

    <h2 class="title is-4"><?php echo $lang["product_buyProduct_productoverview"]; ?></h2>
    <div class="content">
        <p><?php hp($product->text); ?></p>
    </div>
    
    <form method="POST">
        <input type="hidden" name="csrf_token" value="<?php print $_SESSION["csrf_token"]; ?>">
        <input type="hidden" name="state" value="commit">
        <input type="hidden" name="price" value="<?php echo $product->price ?>">
        <input class="button is-info" type="submit" value="<?php echo $lang["product_buyProduct_submit"]; ?>">
    </form>

</div>

<?php
}

#######################################
function ownProducts($user, $lang){
    $products = null;
    if(isset($_GET["title"])){
        $products =
        Product::raw_query("SELECT * FROM products WHERE title LIKE '%" . $_GET["title"] ."%' AND user_id = " . $user->id)->find_many();
    } else {
        $products = Product::raw_query("SELECT * FROM products WHERE user_id = " . $user->id)->find_many();
    }

    $output = isset($_POST["output"])?$_POST["output"]:"";
    if($output == "csv"){

        $state = [
            1 => $lang["product_ownproducts_sell"],
            2 => $lang["product_ownproducts_soldout"],
            3 => $lang["product_ownproducts_stopsale"],
        ];

        $mode = [
            0 => $lang["product_ownproducts_norestriction"],
            1 => $lang["product_ownproducts_mutualfollowers"],
            2 => $lang["product_ownproducts_selectfollower"],
            3 => $lang["product_ownproducts_onlyfollowers"],
        ];

        $filename = $user->id . "_" . date("YmdHis").".csv";
	    $filepath = "/data/csv/".$filename;

	    if(touch($filepath)){
		    $csv = new SplFileObject($filepath,'w');
	        $csv_head = array($lang["product_ownproducts_productname"],$lang["product_ownproducts_productoverview"],$lang["product_ownproducts_productprice"],$lang["product_ownproducts_purchaserestriction"],$lang["product_ownproducts_condition"]);
	        mb_convert_variables('SJIS', 'UTF8', $csv_head);
        	$csv->fputcsv($csv_head);

	        foreach($products as $p) {
	            $csv_data = [
	                "title" => $p->title,
	                "text" => $p->text,
	                "price" => "\\".$p->price,
	                "mode" => $mode[$p->mode],
	                "state" => $state[$p->state],
                	            ];
	            mb_convert_variables('SJIS', 'UTF8', $csv_data);
	            $csv->fputcsv($csv_data);
	        }
        }else{
            $err = $lang["product_ownproducts_makefileerr001"];
        }
    }

    $dir = "/data/csv/";
    $prefix = $user->id . "_";
    $files = [];
    if (is_dir($dir)) {
        if ($dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {
                if (strpos($file, $prefix) === 0 && $file !== '.' && $file !== '..') {
                    $path = $dir . $file;
                    $files[filectime($path)] = $file;
                }
            }
            closedir($dh);
            ksort($files);
        }
    }

    printHeader();

    $selected = "own_product_list";
    include("./links.php");
?>

        </ul>
      </div>
    </nav>
  </div>
</section>

    <?php if (isset($err)) { printError($err); } ?>

    <div class="section is-max-tablet ">
        <form>
            <h2 class="title is-6"><?php echo $lang["product_ownproducts_searchownproduct"]; ?></h2>
            <div class="field is-horizontal">
                <div class="field is-grouped">
                    <p class="control is-expanded">
                        <input class="input is-rounded" type="text" name="title" placeholder="<?php echo $lang["product_ownproducts_productname"]; ?>" value="<?php if(isset($_GET["title"])) print $_GET["title"]; ?>">
                    </p>
                    <input type="hidden" name="mode" value="own">
                    <p class="control">
                        <input class="button is-info is-rounded" type="submit" value="<?php echo $lang["product_ownproducts_searchproduct"]; ?>">
                    </p>
                </div>
	        </div>
        </form>
        <br>
        <div>
			<h2 class="title is-6"><?php echo $lang["product_ownproducts_msg001"]; ?></>
		</div>
    </div>
    <div class="section">
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
                                        <img src="./img.php?id=<?php hp($p->id); ?>" alt="<?php hp($p->title); ?>" >
                                    </p>
                                </figure>

                                <div class="media-content">
                                    <div class="media-right">
                                        <div class="content ">                                        
                                            <p class="title is-4"><?php print h($p->title); ?></h1>
                                            <p class="subtitle is-5"><?php if($p->state != Product::STATE_SELL) print "<s>"; ?><?php print $lang["lib_yen"]; print $p->price; ?><?php if($p->state != Product::STATE_SELL) print "</s>"; ?></p>
                                        </div>
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
    </div>
    <div class="section">
        <div class="box">
            <article class="media">
                <div class="media-left">
                    <aside class="menu">
                        <p class="title is-6"><?php echo $lang["product_ownproducts_csv"]; ?></p>
                        <ul class="menu-list">
                            <?php foreach($files as $f) { ?>
                                <a href="csvdownload.php?filename=<?php echo $f;?>"><?php echo $f;?></a></li>
                                <!-- <li><a href="./csv/<?php echo $f;?>"><?php echo $f;?></a></li> -->
                            <?php } ?>
                        </ul>
                    </aside>
                </div>
                <div class="media-content">
                    <div class="level-right">
                    <form method="POST" action="product.php?mode=own">
                        <input type="hidden" name="output" value="csv" />
                        <input class="button is-info is-rounded" type="submit" name="submit" value="<?php echo $lang["product_ownproducts_submitcsv"]; ?>" />
                    </form>
                    </div>
                </div>
            </article>
        </div>
    </div>

<?php
printFooter();
}
