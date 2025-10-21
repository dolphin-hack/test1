<?php
require_once "./libs.php";
$user = stateUser();
header("Content-type: " . image_type_to_mime_type(IMAGETYPE_JPEG));

if(isset($_GET["tmp"])){
    print file_get_contents(DATA_IMAGEDIR."/".$_SESSION["tmp_filecount_".$_GET["tmp"]]);
} elseif(isset($_GET["id"])){
    $p = Product::find_one($_GET["id"]);
    #var_dump($p);
    if($p && $p->canSee($user) && $p->img != ""){
        print file_get_contents(DATA_IMAGEDIR."/".$p->img);
    } else {
        header("X-notfound: " . DATA_IMAGEDIR."/".$p->img);
        print file_get_contents(DATA_IMAGEDIR."/null");
    }
}
?>