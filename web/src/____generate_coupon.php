<?php
require_once "./libs.php";
$lang = include "lang_ja.php";
$selected = "admin";

// クーポンコードを生成する関数
function generateCouponCode() {
    return sha1(uniqid(mt_rand(), true));
}

$user = stateUser();
if(isset($user) && isset($_POST["amount"])){
    printHeader("Generate Coupon");

    include("./links.php");
?>

        </ul>
      </div>
    </nav>
  </div>
  </section> 

<?php

    $amount = isset($_POST["amount"]) ? $_POST["amount"] : null;

    echo "<div class='section'>";

    if($amount && is_numeric($amount)){ // 0ははじく
        $amount = (int)$amount;

        // 新しいコードを生成
        $newCode = generateCouponCode();

        $ret = Coupon::generate($newCode, $amount);
        if($ret){
            printError("<label class='label'>" . $lang["admin_coupon_gen_msg001"] . ":</label><p><strong>$newCode</strong></p>", "is-info");
        } else {
            printError($lang["admin_coupon_gen_err001"]);
        }
    } else {
        printError($lang["admin_coupon_gen_err002"]);
    }

    echo "</div>";
?>
<hr>
<div class="section">
    <a href="/____generate_coupon.php"><?php echo $lang["admin_coupon_gen_link001"]; ?></a>
<!-- <a href="/coupon.php">UseCouponCode</a><br> -->
</div>
<?php
    // footer
    printFooter();
} elseif (isset($user)){
    printHeader("Generate Coupon");
    include("./links.php");
    $coupons = Coupon::getAvailableCodes();
    ?>

        </ul>
      </div>
    </nav>
  </div>
  </section> 

<div class="section">
    <form method="POST" action="/____generate_coupon.php">
        <label class="label"><?php echo $lang["admin_coupon_gen_amount"]; ?>: </label>
        <div class="field has-addons">
            <div class="control">
                <input class="input" name="amount" type="number"></input>&nbsp;
            </div>
            <div class="control">
                <input class="button" type="submit" value="<?php echo $lang["admin_coupon_gen_submit"]; ?>"></input>
            </div>
        </div>
    </form>
    <hr>
        <?php
    if ($coupons && count($coupons) > 0): ?>
    <!-- <a href="/coupon.php">UseCouponCode</a><br> -->
    <br>
    <p><?php echo $lang["admin_coupon_gen_msg002"]; ?></p><br>
        <table border="1" class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
            <thead>
                <tr>
                    <th><?php echo $lang["admin_coupon_gen_id"]; ?></th>
                    <th><?php echo $lang["admin_coupon_gen_createat"]; ?></th>
                    <th><?php echo $lang["admin_coupon_gen_code"]; ?></th>
                    <th><?php echo $lang["admin_coupon_gen_amount"]; ?></th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($coupons as $coupon):
                $url = "http://{$_SERVER['HTTP_HOST']}/coupon.php?coupon.code={$coupon->code}&coupon.amount={$coupon->amount}&timestamp={$coupon->timestamp}&id={$coupon->id}";
                ?>
                <tr>
                    <td><?= htmlspecialchars($coupon->id) ?></td>
                    <td><?= htmlspecialchars($coupon->timestamp) ?></td>
                    <td><a href="<?= htmlspecialchars($url) ?>"><?= htmlspecialchars($coupon->code) ?></a></td>
                    <td><?= htmlspecialchars($coupon->amount) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="title is-5"><?php echo $lang["admin_coupon_gen_msg003"]; ?></p>
    <?php endif; ?>
</div>

<?php
    // footer
    printFooter();
}
?>
