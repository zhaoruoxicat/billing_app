<?php
session_start();
require 'auth.php'; // 如果你有权限控制
?>
<?php
require 'db.php';
$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM takeout_channels WHERE id = ?");
$stmt->execute([$id]);
$channel = $stmt->fetch();
if (!$channel) exit('渠道不存在');
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="UTF-8">
  <title>编辑渠道</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="theme.css">
</head>
<body class="bg-light">
<div class="container py-4">
  <h2>编辑点餐渠道</h2>
  <form method="post" action="takeout_update_channel.php">
    <input type="hidden" name="id" value="<?= $channel['id'] ?>">
    <div class="mb-3">
      <label class="form-label">渠道名称</label>
      <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($channel['name']) ?>" required>
    </div>
    <button class="btn btn-primary">保存修改</button>
    <a href="takeout_channels.php" class="btn btn-secondary">取消</a>
  </form>
</div>
</body>
</html>
