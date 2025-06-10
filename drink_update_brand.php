<?php
session_start();
require 'auth.php'; // 如果你有权限控制
?>
<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id'];
    $name = trim($_POST['name']);

    if ($name !== '') {
        $stmt = $pdo->prepare("UPDATE drink_brands SET name = ? WHERE id = ?");
        $stmt->execute([$name, $id]);
    }
}

header("Location: drink_brands.php");
exit;
