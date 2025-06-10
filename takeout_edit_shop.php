<?php
session_start();
require 'auth.php'; // 如果你有权限控制
?>
<?php
require 'db.php';
$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM takeout_shops WHERE id = ?");
$stmt->execute([$id]);
$shop = $stmt->fetch();

if (!$shop) exit('店铺不存在');
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="UTF-8">
  <title>编辑店铺</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="theme.css">
</head>
<body class="bg-light">
<div class="container py-4">
  <h2>编辑外卖店铺</h2>

  <form method="post" action="takeout_update_shop.php" class="mb-4">
    <input type="hidden" name="id" value="<?= $shop['id'] ?>">
    <div class="mb-3">
      <label class="form-label">店铺名称</label>
      <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($shop['name']) ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">保存修改</button>
    <a href="takeout_shops.php" class="btn btn-secondary">取消</a>
  </form>
</div>
</body>
</html>
