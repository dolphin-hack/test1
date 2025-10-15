<?php
require_once "./libs.php";
$lang = include "lang_ja.php";
$selected = "own_product_list";
$user = stateUser();
if(!$user){
    header("Location: ./login.php?text=%E3%81%93%E3%81%AE%E6%A9%9F%E8%83%BD%E3%82%92%E4%BD%BF%E3%81%86%E3%81%AB%E3%81%AF%E3%83%AD%E3%82%B0%E3%82%A4%E3%83%B3%E3%81%8C%E5%BF%85%E8%A6%81%E3%81%A7%E3%81%99"); #この機能を使うにはログインが必要です
    exit();
}


$filename = "/data/csv/".$_GET["filename"];

if($filename && file_exists($filename) ){
	header('Cache-Control: private');
	header('Content-Type: application/octet-stream');
	header('Content-Transfer-Encoding: binary');
	header('Content-Length: ' . filesize($filename));
	header('Content-Disposition: attachment; filename="' . h(basename($filename)) . '"');
	readfile( $filename );
	exit;
} else {
	$err = h($filename) . $lang["csvdl_err001"];
}
?>


<?php
    printHeader();
    include("./links.php");
?>

        </ul>
      </div>
    </nav>
  </div>
</section>


<div class="media-content">
<?php
    echo h($err);
?>
</div>

<?php
    printFooter();
?>





