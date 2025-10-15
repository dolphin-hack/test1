<?php
$isact = 'class="is-active"';
$user = isset($user) ? $user : "";
if($user){
?>
        <li <?php echo ($selected === 'home') ? $isact : "" ?>><a href="./index.php" class="pure-menu-link"><?php echo $lang["home"]; ?></a></li>
        <li <?php echo ($selected === 'search') ? $isact : "" ?>><a href="./product.php" class="pure-menu-link"><?php echo $lang["search"]; ?></a></li>
        <li <?php echo ($selected === 'new_item') ? $isact : "" ?>><a href="./product.php?mode=make&id=new" class="pure-menu-link"><?php echo $lang["new_item"]; ?></a></li>
        <li <?php echo ($selected === 'own_product_list') ? $isact : "" ?>><a href="./product.php?mode=own" class="pure-menu-link"><?php echo $lang["own_product_list"]; ?></a></li>
        <li <?php echo ($selected === 'user_list') ? $isact : "" ?>><a href="./userlist.php" class="pure-menu-link"><?php echo $lang["user_list"]; ?></a></li>
        <li <?php echo ($selected === 'send_mail') ? $isact : "" ?>><a href="./sendmail.php" class="pure-menu-link"><?php echo $lang["send_mail"]; ?></a></li>
        <li <?php echo ($selected === 'charge') ? $isact : "" ?>><a href="./coupon.php" class="pure-menu-link"><?php echo $lang["charge"]; ?></a></li>
        <?php echo AdminLink($selected); ?>
        <li <?php echo ($selected === 'account_setting') ? $isact : "" ?>><a href="./account.php" class="pure-menu-link"><?php echo $lang["account_setting"]; ?></a></li>
<?php
} else {
?>
        <li <?php echo ($selected === 'home') ? $isact : "" ?>><a href="./index.php" ><?php echo $lang["home"]; ?></a></li>
        <li <?php echo ($selected === 'login') ? $isact : "" ?>><a href="./login.php" ><?php echo $lang["login"]; ?></a></li>
        <li <?php echo ($selected === 'register') ? $isact : "" ?>><a href="./register.php" ><?php echo $lang["register"]; ?></a></li>
<?php
}