<?php
session_start();
include("functions.php");
check_session_id();

$pdo = connect_to_db();
$user_id = $_SESSION['id'];



// $sql = "SELECT * FROM jimoto_table";
// $sql = 'SELECT * FROM jimoto_table
//           LEFT OUTER JOIN (SELECT todo_id, COUNT(id) AS cnt
//            FROM like_table GROUP BY todo_id) AS likes
//           ON .id = likes._id';
$sql = 'SELECT * FROM jimoto_table
          LEFT OUTER JOIN (SELECT todo_id, COUNT(id) AS cnt
          FROM like_table GROUP BY todo_id) AS likes
          ON jimoto_table.id = likes.todo_id';
$stmt = $pdo->prepare($sql);
$status = $stmt->execute();

if ($status == false) {
  $error = $stmt->errorInfo();
  echo json_encode(["error_msg" => "{$error[2]}"]);
  exit();
} else {
  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $output = "";
  $target = $record["fname"];
  foreach ($result as $record) {
    $output .= "<tr>";
    $output .= "<td>{$record["area"]}</td>";
    $output .= "<td>{$record["product"]}</td>";
    $output .= "<img src='import_media.php?target=$target'>";
    // cntカラムの数値(いいね数)を追加
    $output .= "<td><a href='like_create.php?user_id={$user_id}&todo_id={$record["id"]}'>
    like{$record["cnt"]}  </a>
            </td>";

    $output .= "<td><a href='todo_edit.php?id={$record["id"]}'></a></td>";
    $output .= "<td><a href='todo_delete.php?id={$record["id"]}'>delete</a></td>";
    $output .= "</tr>";
  }
  unset($value);
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>スターバックス（一覧画面）</title>
</head>

<body>
  <fieldset>
    <legend>スターバックス（一覧画面）</legend>
    <a href="todo_input.php">入力画面</a>
    <a href="todo_logout.php">logout</a>
    <table>
      <thead>
        <tr>
          <th>area</th>
          <th>product</th>
          <th></th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <!-- ここに<tr><td>deadline</td><td>todo</td><tr>の形でデータが入る -->
        <?= $output ?>
      </tbody>
    </table>
  </fieldset>
</body>

</html>