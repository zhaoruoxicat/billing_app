<?php
session_start();
require 'auth.php'; // 如果你有权限控制
?>
<?php
require 'db.php';
$name = trim($_POST['name'] ?? '');
if ($name !== '') {
    $stmt = $pdo->prepare("INSERT IGNORE INTO daily_channels (name) VALUES (?)");
    $stmt->execute([$name]);
}
header("Location: daily_channels.php");
exit;
