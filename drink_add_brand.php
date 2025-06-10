<?php
session_start();
require 'auth.php'; // 如果你有权限控制
?>
<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);

    if ($name !== '') {
        $stmt = $pdo->prepare("INSERT IGNORE INTO drink_brands (name) VALUES (?)");
        $stmt->execute([$name]);
    }
}

header("Location: drink_brands.php");
exit;
