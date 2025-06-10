<?php
session_start();
require 'auth.php'; // 如果你有权限控制
?>
<?php
require 'db.php';
$id = (int)$_POST['id'];
$name = trim($_POST['name']);
if ($name !== '') {
    $stmt = $pdo->prepare("UPDATE daily_channels SET name = ? WHERE id = ?");
    $stmt->execute([$name, $id]);
}
header("Location: daily_channels.php");
exit;
