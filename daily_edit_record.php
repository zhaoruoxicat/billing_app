<?php
session_start();
require 'auth.php'; // 如果你有权限控制
?>
<?php
require 'db.php';
$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM daily_records WHERE id = ?");
$stmt->execute([$id]);
$record = $stmt->fetch();
if (!$record) exit('记录不存在');

// 加载所有选项
$channels = $pdo->query("SELECT * FROM daily_channels ORDER BY name")->fetchAll();
$platforms = $pdo->query("SELECT * FROM daily_platforms ORDER BY name")->fetchAll();
$methods = $pdo->query("SELECT * FROM daily_methods ORDER BY name")->fetchAll();
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="UTF-8">
  <title>编辑日常消费记录</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="theme.css">
</head>
<body class="bg-light">
<div class="container py-4">
  <h2>编辑日常消费记录</h2>

  <form method="post" action="daily_update_record.php">
    <input type="hidden" name="id" value="<?= $record['id'] ?>">

    <div class="mb-3">
      <label class="form-label">消费渠道</label>
      <select name="channel_id" class="form-select" required>
        <?php foreach ($channels as $c): ?>
          <option value="<?= $c['id'] ?>" <?= $c['id'] == $record['channel_id'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($c['name']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label">支付平台</label>
      <select name="platform_id" class="form-select" required>
        <?php foreach ($platforms as $p): ?>
          <option value="<?= $p['id'] ?>" <?= $p['id'] == $record['platform_id'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($p['name']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label">支付方式</label>
      <select name="method_id" class="form-select" required>
        <?php foreach ($methods as $m): ?>
          <option value="<?= $m['id'] ?>" <?= $m['id'] == $record['method_id'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($m['name']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label">金额</label>
      <input type="number" step="0.01" name="price" class="form-control" value="<?= $record['price'] ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label">备注</label>
      <input type="text" name="remark" class="form-control" value="<?= htmlspecialchars($record['remark']) ?>">
    </div>

    <div class="mb-3">
      <label class="form-label">日期</label>
      <input type="date" name="purchase_date" class="form-control" value="<?= $record['purchase_date'] ?>" required>
    </div>

    <button type="submit" class="btn btn-primary">保存</button>
    <a href="daily_index.php" class="btn btn-secondary">取消</a>
  </form>
</div>
</body>
</html>
