<?php
require 'db.php';
require 'auth.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csv_file'])) {
    $file = $_FILES['csv_file']['tmp_name'];

    if (($handle = fopen($file, 'r')) !== false) {
        $rowCount = 0;
        $header = fgetcsv($handle); // 跳过表头

        while (($data = fgetcsv($handle)) !== false) {
            [$shopName, $channelName, $price, $date] = $data;

            // 获取或插入店铺
            $stmt = $pdo->prepare("SELECT id FROM takeout_shops WHERE name = ?");
            $stmt->execute([$shopName]);
            $shopId = $stmt->fetchColumn();

            if (!$shopId) {
                $stmt = $pdo->prepare("INSERT INTO takeout_shops (name) VALUES (?)");
                $stmt->execute([$shopName]);
                $shopId = $pdo->lastInsertId();
            }

            // 获取或插入渠道
            $stmt = $pdo->prepare("SELECT id FROM takeout_channels WHERE name = ?");
            $stmt->execute([$channelName]);
            $channelId = $stmt->fetchColumn();

            if (!$channelId) {
                $stmt = $pdo->prepare("INSERT INTO takeout_channels (name) VALUES (?)");
                $stmt->execute([$channelName]);
                $channelId = $pdo->lastInsertId();
            }

            // 插入外卖记录
            $stmt = $pdo->prepare("INSERT INTO takeout_records (shop_id, channel_id, price, purchase_date) VALUES (?, ?, ?, ?)");
            $stmt->execute([$shopId, $channelId, (float)$price, $date]);

            $rowCount++;
        }
        fclose($handle);
        $success = "成功导入 $rowCount 条记录";
    } else {
        $error = "无法读取上传的文件";
    }
}
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="UTF-8">
  <title>导入外卖记录</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
  <h3 class="mb-4">导入外卖记录（CSV 文件）</h3>

  <?php if ($success): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
  <?php elseif ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="post" enctype="multipart/form-data" class="mb-4">
    <div class="mb-3">
      <label for="csv_file" class="form-label">选择 CSV 文件</label>
      <input type="file" name="csv_file" id="csv_file" class="form-control" accept=".csv" required>
    </div>
    <button class="btn btn-primary">上传并导入</button>
    <a href="takeout_index.php" class="btn btn-secondary">返回外卖记录</a>
  </form>

  <div class="alert alert-info">
    <strong>CSV 格式说明：</strong><br>
    表头必须为：<code>店铺,渠道,金额,日期</code><br>
    示例行：<br>
    肯德基,美团拼好饭,25.5,2025-06-01<br>
    麦当劳,京东外卖,19,2025-06-01
  </div>
</div>
</body>
</html>
