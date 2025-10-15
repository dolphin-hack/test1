<?php
require_once "./libs.php";
$lang = include "lang_ja.php";
$selected = "home";

$user = stateUser();
printHeader();
$products = array();

if($user){
    $mybag = Bag::where("user_id",$user->id)->find_many();
    foreach($mybag as $bag){
        $products[] = Product::find_one($bag->product_id);
    }

    include("./links.php");
 ?>
        </ul>
      </div>
</div>

    </div>

    </nav>    
  </div>
</section>
<script>
function heartbeat(){
    const t = new Date();
    fetch("./hb.php", {
        method: "POST",
        cache: "no-cache",
        credentials: "same-origin",
        headers: {
        "X-HeartBeat":t.getTime()
        }
    }).then((r) => r.text())
    .then((d) => console.log(d));

    setTimeout(() => {
        heartbeat();        
    }, 10000);
}
heartbeat();
</script>

<div class="grid">
    <div class="cell">
        <div class="section">        
            <h2 class="title"><?php echo $lang["index_rcv_mail"]; ?></h2>
            <ul>
                <?php
                    $messages = $user->readMessages();
                    foreach($messages as $m){
                        echo '<article class="media"><a href="./readmail.php?id=' . h($m->id) . '"><div class="media-content"><div class="content"><p><strong class="subtitle is-4">'.h($m->from_user()->name)."</strong><br />" . $m->title . "<br /></a></p></div></div></a></article>";
                    }
                ?>
            </ul>
        </div>
    </div>

    <div class="cell">
        <div class="section">
            <h2 class="title"><?php echo $lang["index_snd_mail"]; ?></h2>
            <ul>
                <?php
                    $messages = $user->writeMessages();
                    foreach($messages as $m){
                        echo '<article class="media"><a href="./readmail.php?id=' . h($m->id) . '"><div class="media-content"><div class="content"><p><strong class="subtitle is-4">'.h($m->from_user()->name)."</strong><br />" . $m->title . "<br /></a></p></div></div></a></article>";
                    }
                ?>
            </ul>
        </div>
    </div>
</div>

<div class="section">
    <h2 class="title"><?php echo $lang["index_purchases"]; ?></h2>
    <!-- top rating products -->
    <div class="fixed-grid has-3-cols">
        <div class="grid">
        <?php foreach($products as $proc){ ?>
        <div class="block">
            <div class="box">
                <div class="cell">
                    <a href="./product.php?mode=show&amp;id=<?php hp($proc->id); ?>">
                    <article class="media" >
                        <figure class="media-left">
                            <p class="image is-128x128">
                                <img src="./img.php?id=<?php hp($proc->id); ?>" alt=<?php hp($proc->title); ?>>
                            </p>
                        </figure>
                        <div class="media-content">
                        <div class="media-right">
                            <p class="title is-4"><?php print h($proc->title); ?></p>
                            <p class="subtitle is-5"><?php print $lang["lib_yen"]; hp($proc->price) ?></p>
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

<?php
    }else{
        $products = Product::getProductList();
        $i=0;
   ?>

    <div class="hero-foot">
    <nav class="tabs is-boxed ">
        <div class="container">
        <ul>
    <?php 
    include("./links.php");
    ?>
        </ul>
        </div>
    </nav>
    </div>
</section>


<div class="section has-text-centered">
<h3 class="title is-4 "><?php echo $lang["index_msg001"]; ?></h3>
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
                                <div class="content">
                                    <p class="title is-4"><?php print h($p->title); ?></p>
                                    <p class="subtitle is-5"><?php if($p->state != Product::STATE_SELL) print "<s>"; ?>￥<?php print $p->price; ?><?php if($p->state != Product::STATE_SELL) print "</s>"; ?></p>
                                    </div>
                                </div>
                            </div>
                        </article>
                        </a>
                    </div>
                </div>
            </div>
            <?php if($i<2){$i++;}else{break;}} ?>
        </div>
    </div>
</div>
<?php
}

printFooter();
