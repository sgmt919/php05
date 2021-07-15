<?php
session_start();
include("functions.php");
check_session_id();

$pdo = connect_to_db();

//DBから取得して表示する．
// $sql = "SELECT * FROM jimoto_table ORDER BY id;";
// $stmt = $pdo->prepare($sql);
// $stmt->execute();
// while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
//   echo ($row["id"] . "<br/>");
//   echo ($row["area"] . "<br/>");
//   echo ($row["product"] . "<br/>");

//   //動画と画像で場合分け
//   $target = $row["fname"];
//   if ($row["extension"] == "mp4") {
//     echo ("<video src=\"import_media.php?target=$target\" width=\"213\" height=\"120\" controls></video>");
//   } elseif ($row["extension"] == "jpeg" || $row["extension"] == "png" || $row["extension"] == "gif") {
//     echo ("<img src='import_media.php?target=$target'>");
//   }


//   echo ("<br/><br/>");
// }
