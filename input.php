<?php
session_start();
include("functions.php");
check_session_id();

$pdo = connect_to_db();
$area = $_POST['area'];
$product = $_POST['product'];


//ファイルアップロードがあったとき
if (isset($_FILES['upfile']['error']) && is_int($_FILES['upfile']['error']) && $_FILES["upfile"]["name"] !== "") {
    //エラーチェック
    switch ($_FILES['upfile']['error']) {
        case UPLOAD_ERR_OK: // OK
            break;
        case UPLOAD_ERR_NO_FILE:   // 未選択
            throw new RuntimeException('ファイルが選択されていません', 400);
        case UPLOAD_ERR_INI_SIZE:  // php.ini定義の最大サイズ超過
            throw new RuntimeException('ファイルサイズが大きすぎます', 400);
        default:
            throw new RuntimeException('その他のエラーが発生しました', 500);
    }

    //画像・動画をバイナリデータにする．
    $raw_data = file_get_contents($_FILES['upfile']['tmp_name']);

    //拡張子を見る
    $tmp = pathinfo($_FILES["upfile"]["name"]);
    $extension = $tmp["extension"];
    if ($extension === "jpg" || $extension === "jpeg" || $extension === "JPG" || $extension === "JPEG") {
        $extension = "jpeg";
    } elseif ($extension === "png" || $extension === "PNG") {
        $extension = "png";
    } elseif ($extension === "gif" || $extension === "GIF") {
        $extension = "gif";
    } elseif ($extension === "mp4" || $extension === "MP4") {
        $extension = "mp4";
    } else {
        echo "非対応ファイルです．<br/>";
        echo ("<a href=\"input.php\">戻る</a><br/>");
        exit(1);
    }

    //DBに格納するファイルネーム設定
    //サーバー側の一時的なファイルネームと取得時刻を結合した文字列にsha256をかける．
    $date = getdate();
    $fname = $_FILES["upfile"]["tmp_name"] . $date["year"] . $date["mon"] . $date["mday"] . $date["hours"] . $date["minutes"] . $date["seconds"];
    $fname = hash("sha256", $fname);

    //画像・動画をDBに格納．
    $sql = "INSERT INTO jimoto_table(id, fname, extension, raw_data, area, product,created_at) VALUES (NULL, :fname, :extension, :raw_data, :area, :product, sysdate());";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(":fname", $fname, PDO::PARAM_STR);
    $stmt->bindValue(":extension", $extension, PDO::PARAM_STR);
    $stmt->bindValue(":raw_data", $raw_data, PDO::PARAM_STR);
    $stmt->bindValue(":area", $area, PDO::PARAM_STR);
    $stmt->bindValue(":product", $product, PDO::PARAM_STR);
    $stmt->execute();
}

?>

<!DOCTYPE HTML>

<html lang="ja">

<head>
    <meta charset="utf-8">
    <title>media</title>
</head>

<body>
    <img src="data:image/png;base64,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">
    <br><a href="todo_read.php">一覧画面</a>
    <br><a href="todo_logout.php">logout</a>
    <form action="input.php" enctype="multipart/form-data" method="post">
        <label>画像/動画アップロード</label>
        <input type="file" name="upfile"><br>
        エリア：<input type="text" name="area"><br>
        商品名：<input type="text" name="product"><br>

        ※画像はjpeg方式，png方式，gif方式に対応しています．動画はmp4方式のみ対応しています．<br>
        <input type="submit" value="アップロード">
    </form>
    <?php
    //DBから取得して表示する．
    $sql = "SELECT * FROM jimoto_table ORDER BY id;";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo ($row["id"] . "<br />");
        echo ($row["area"] . "<br />");
        echo ($row["product"] . "<br />");
        //動画と画像で場合分け
        $target = $row["fname"];
        if ($row["extension"] == "mp4") {
            echo ("<video src=\"import_media.php?target=$target\" width=\"213\" height=\"120\" controls></video>");
        } elseif ($row["extension"] == "jpeg" || $row["extension"] == "png" || $row["extension"] == "gif") {
            echo ("<img src='import_media.php?target=$target'>");
        }
        echo "<td><a href='like_create.php?user_id={$user_id}&todo_id={$row["id"]}'>
         like{$row["cnt"]}</a></td>";

        echo "<td><a href='todo_edit.php?id={$row["id"]}'></a></td>";
        echo "<td><a href='todo_delete.php?id={$row["id"]}'>delete</a></td>";

        echo ("<br /><br />");
    }
    ?>


</body>

</html>