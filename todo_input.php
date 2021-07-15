<?php
session_start();
include("functions.php");
check_session_id();
$pdo = connect_to_db();
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>スタバ（入力画面）</title>
</head>

<body>
  <img src="https://www.starbucks.co.jp/">
  <form action="input.php" method="post" enctype="multipart/form-data">
    <p>アップロード画像</p>
    <input type="file" name="image">
    エリア：<input type="text" name="area">
    商品名：<input type="text" name="product">
    <button><input type="submit" name="upload" value="送信"></button>
  </form>

</body>

</html>