<?php
session_start();
require 'auth.php'; // 如果你有权限控制
?>
<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $channel_id = (int)$_POST['channel_id'];
    $platform_id = (int)$_POST['platform_id'];
    $method_id = (int)$_POST['method_id'];
    $price = (float)$_POST['price'];
    $remark = trim($_POST['remark'] ?? '');
    $date = $_POST['purchase_date'];

    $stmt = $pdo->prepare("INSERT INTO daily_records (channel_id, platform_id, method_id, price, remark, purchase_date) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$channel_id, $platform_id, $method_id, $price, $remark, $date]);
}

header("Location: daily_index.php");
exit;
