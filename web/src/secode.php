<!doctype>
<html>
<head>
<title>seccodeを求めるプログラム</title>
</head>
<body>
<form>
カード番号：<input type="text" name="card">
<input type="submit">
</form>
<p>
<?php
if(isset($_GET["card"])){
    print substr(sha1($_GET["card"]."Jinx means the jinx."),0,6);
}
?>
</p>
</body>
</html>