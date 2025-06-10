<?php
session_start();
require 'auth.php'; // 如果你有权限控制
?>
<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id'];
    $shop_id = (int)$_POST['shop_id'];
    $channel_id = (int)$_POST['channel_id'];
    $price = (float)$_POST['price'];
    $date = $_POST['purchase_date'];

    $stmt = $pdo->prepare("
        UPDATE takeout_records
        SET shop_id = ?, channel_id = ?, price = ?, purchase_date = ?
        WHERE id = ?
    ");
    $stmt->execute([$shop_id, $channel_id, $price, $date, $id]);
}

header("Location: takeout_index.php");
exit;
