<?php
session_start();
require 'auth.php'; // 如果你有权限控制
?>
<?php
require 'db.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM drink_records WHERE id = ?");
    $stmt->execute([$id]);
}

header("Location: drink_index.php");
exit;
