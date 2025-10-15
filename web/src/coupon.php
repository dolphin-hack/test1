<?php
require_once "./libs.php";
$lang = include "lang_ja.php";
$selected = "charge";

$user = stateUser();
if(isset($user) && isset($_POST["code"])){
    printHeader("Coupon");

    include("./links.php");
?>

        </ul>
      </div>
    </nav>
  </div>
  </section> 

<?php

    $code = @$_POST["code"];

    $coupon = Coupon::isAvailable($code);
    echo "<div class='section'>";
    if($coupon){
        if(Coupon::update($user, $code)){
            printError("<p>{$lang["coupon_apply_msg001"]}{$coupon->amount}{$lang["coupon_apply_msg002"]}</p>","is-info");
        } else {
            printError("<p class=''>{$lang["coupon_apply_err001"]}</p>");
        }
    } else {
        printError("<p class=''>{$lang["coupon_apply_err002"]}</p>");
    }

    echo "</div>";
?>
<hr>
<!--
<a href="/coupon.php">UseCouponCode</a>
-->
<?php
    // footer
    printFooter();
} elseif (isset($user)){
    printHeader("Coupon");
    include("./links.php");
    $chistory = PointHistory::getChargeHistory($user->id);
    ?>

        </ul>
      </div>
    </nav>
  </div>
  </section> 

<div class="section">
    <h3 class="title is-4"><?php echo $lang["coupon_title"]; ?></h3>
    <p class="subtitle is-6"><?php echo $lang["coupon_msg001"]; ?></p>
    <form method="POST" action="/coupon.php">
        <label class="label" id="label"><?php echo $lang["coupon_couponcode"]; ?></label>
        <div class="field has-addons">
            <div class="control">
                <input class="input" name="code" type="text" id="code"></input>&nbsp;
            </div>
            <div class="control">
                <input class="button" type="submit"></input>
            </div>
        </div>
    </form>
    <p class="help"><?php echo $lang["coupon_help"]; ?></p>
    <hr>
    <?php
    if ($chistory && count($chistory) > 0): ?>
    <h3 class="title is-5"><?php echo $lang["coupon_history001"]; ?></h3>
    <p class="subtitle is-6"><?php echo $lang["coupon_msg002"]; ?></p>
        <table border="1" class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
            <thead>
                <tr>
                    <th><?php echo $lang["coupon_id"]; ?></th>
                    <th><?php echo $lang["coupon_date"]; ?></th>
                    <th><?php echo $lang["coupon_amount"]; ?></th>
                </tr>
            </thead>
            <tbody>
            <?php $i = 1; foreach ($chistory as $ch): ?>
                <tr>
                    <td><?= htmlspecialchars($i) ?></td>
                    <td><?= htmlspecialchars($ch->timestamp) ?></td>
                    <td><?= htmlspecialchars($ch->amount) ?></td>
                </tr>
            <?php $i++; endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="title is-5"><?php echo $lang["coupon_history002"]; ?></p>
    <?php endif; ?>

</div>

<?php
    // footer
    printFooter();
} else {
  header("Location: ./login.php");
  exit();
}
?>

<script>

    function setCoupon( coupon ) {
        $("#code").val( coupon.code );
        $("#label").append( $("<span style='color: gray'>").text(coupon.amount + "pt") );
    }

    $(function(){
        const params = { } 
        for( const p of location.search.substr(1).split("&"))
        {
            const kv = p.split("=");
            justSet(params,kv[0],decodeURIComponent(kv[1]));
        }
        if(params.coupon) setCoupon(params.coupon);
    })

</script>
