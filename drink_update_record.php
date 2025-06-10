<?php
session_start();
require 'auth.php'; // 如果你有权限控制
?>
<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id'];
    $brand_id = (int)$_POST['brand_id'];
    $price = (float)$_POST['price'];
    $date = $_POST['purchase_date'];

    $stmt = $pdo->prepare("UPDATE drink_records SET brand_id = ?, price = ?, purchase_date = ? WHERE id = ?");
    $stmt->execute([$brand_id, $price, $date, $id]);
}

header("Location: drink_index.php");
exit;
