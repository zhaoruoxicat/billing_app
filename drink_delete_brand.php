<?php
session_start();
require 'auth.php'; // 如果你有权限控制
?>
<?php
require 'db.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    // 删除品牌记录（如果启用了 ON DELETE CASCADE，关联消费记录也会自动删除）
    $stmt = $pdo->prepare("DELETE FROM drink_brands WHERE id = ?");
    $stmt->execute([$id]);
}

header("Location: drink_brands.php");
exit;
