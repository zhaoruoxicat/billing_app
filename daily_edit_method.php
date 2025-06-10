<?php
session_start();
require 'auth.php'; // 如果你有权限控制
?>
<?php
require 'db.php';
$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM daily_methods WHERE id = ?");
$stmt->execute([$id]);
$method = $stmt->fetch();
if (!$method) exit('支付方式不存在');
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="UTF-8">
  <title>编辑支付方式</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="theme.css">
</head>
<body class="bg-light">
<div class="container py-4">
  <h2>编辑支付方式</h2>

  <form method="post" action="daily_update_method.php">
    <input type="hidden" name="id" value="<?= $method['id'] ?>">
    <div class="mb-3">
      <label class="form-label">方式名称</label>
      <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($method['name']) ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">保存修改</button>
    <a href="daily_methods.php" class="btn btn-secondary">取消</a>
  </form>
</div>
</body>
</html>
