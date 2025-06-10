<?php
session_start();
require 'auth.php'; // 如果你有权限控制
?>
<?php
require 'db.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("
  SELECT * FROM takeout_records WHERE id = ?
");
$stmt->execute([$id]);
$record = $stmt->fetch();

if (!$record) {
    exit("记录不存在");
}

// 获取店铺和渠道列表
$shops = $pdo->query("SELECT id, name FROM takeout_shops ORDER BY name ASC")->fetchAll();
$channels = $pdo->query("SELECT id, name FROM takeout_channels ORDER BY name ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="UTF-8">
  <title>编辑外卖记录</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="theme.css">
</head>
<body class="bg-light">
<div class="container py-4">
  <h2>编辑外卖记录</h2>
  <form method="post" action="takeout_update_record.php">
    <input type="hidden" name="id" value="<?= $record['id'] ?>">

    <div class="mb-3">
      <label class="form-label">店铺</label>
      <select name="shop_id" class="form-select" required>
        <?php foreach ($shops as $s): ?>
          <option value="<?= $s['id'] ?>" <?= $s['id'] == $record['shop_id'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($s['name']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label">渠道</label>
      <select name="channel_id" class="form-select" required>
        <?php foreach ($channels as $c): ?>
          <option value="<?= $c['id'] ?>" <?= $c['id'] == $record['channel_id'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($c['name']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label">金额（元）</label>
      <input type="number" step="0.01" name="price" class="form-control" value="<?= $record['price'] ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label">日期</label>
      <input type="date" name="purchase_date" class="form-control" value="<?= $record['purchase_date'] ?>" required>
    </div>

    <button class="btn btn-primary">保存修改</button>
    <a href="takeout_index.php" class="btn btn-secondary">取消</a>
  </form>
</div>
</body>
</html>
