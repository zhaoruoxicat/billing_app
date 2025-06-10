<?php
session_start();
require 'auth.php'; // 如果你有权限控制
?>
<?php
require 'db.php';
$id = (int)($_GET['id'] ?? 0);
$pdo->prepare("DELETE FROM daily_channels WHERE id = ?")->execute([$id]);
header("Location: daily_channels.php");
exit;
