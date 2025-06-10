<?php
session_start();
require 'auth.php'; // 如果你有权限控制
?>
<?php
require 'db.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("DELETE FROM daily_records WHERE id = ?");
$stmt->execute([$id]);

header("Location: daily_index.php");
exit;
