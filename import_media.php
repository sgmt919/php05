<?php
session_start();
include("functions.php");
check_session_id();

$pdo = connect_to_db();


if (isset($_GET["target"]) && $_GET["target"] !== "") {
    $target = $_GET["target"];
} else {
    header("Location:input.php");
}
$MIMETypes = array(
    'png' => 'image/png',
    'jpeg' => 'image/jpeg',
    'gif' => 'image/gif',
    'mp4' => 'video/mp4'
);

$sql = "SELECT * FROM jimoto_table WHERE fname = :target;";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(":target", $target, PDO::PARAM_STR);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
header("Content-Type: " . $MIMETypes[$row["extension"]]);
echo ($row["raw_data"]);
