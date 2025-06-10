<?php
session_start();
require 'auth.php'; // 如果你有权限控制
?>
<?php
require 'db.php';

$id = (int)$_POST['id'];
$channel_id = (int)$_POST['channel_id'];
$platform_id = (int)$_POST['platform_id'];
$method_id = (int)$_POST['method_id'];
$price = (float)$_POST['price'];
$remark = trim($_POST['remark']);
$date = $_POST['purchase_date'];

$stmt = $pdo->prepare("
  UPDATE daily_records
  SET channel_id = ?, platform_id = ?, method_id = ?, price = ?, remark = ?, purchase_date = ?
  WHERE id = ?
");
$stmt->execute([$channel_id, $platform_id, $method_id, $price, $remark, $date, $id]);

header("Location: daily_index.php");
exit;
