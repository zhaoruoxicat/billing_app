<?php
session_start();
require 'auth.php'; // 如果你有权限控制
?>
<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brand_id = (int)$_POST['brand_id'];
    $price = (float)$_POST['price'];
    $date = $_POST['purchase_date'];

    $stmt = $pdo->prepare("INSERT INTO drink_records (brand_id, price, purchase_date) VALUES (?, ?, ?)");
    $stmt->execute([$brand_id, $price, $date]);
}

header("Location: drink_index.php");
exit;
