<?php
require 'db.php';
require 'auth.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csv_file'])) {
    $file = $_FILES['csv_file']['tmp_name'];

    if (($handle = fopen($file, 'r')) !== false) {
        $header = fgetcsv($handle); // 跳过表头
        $rowCount = 0;

        while (($data = fgetcsv($handle)) !== false) {
            [$channelName, $platformName, $methodName, $price, $remark, $date] = $data;

            // 渠道
            $stmt = $pdo->prepare("SELECT id FROM daily_channels WHERE name = ?");
            $stmt->execute([$channelName]);
            $channelId = $stmt->fetchColumn();
            if (!$channelId) {
                $stmt = $pdo->prepare("INSERT INTO daily_channels (name) VALUES (?)");
                $stmt->execute([$channelName]);
                $channelId = $pdo->lastInsertId();
            }

            // 平台
            $stmt = $pdo->prepare("SELECT id FROM daily_platforms WHERE name = ?");
            $stmt->execute([$platformName]);
            $platformId = $stmt->fetchColumn();
            if (!$platformId) {
                $stmt = $pdo->prepare("INSERT INTO daily_platforms (name) VALUES (?)");
                $stmt->execute([$platformName]);
                $platformId = $pdo->lastInsertId();
            }

            // 方式
            $stmt = $pdo->prepare("SELECT id FROM daily_methods WHERE name = ?");
            $stmt->execute([$methodName]);
            $methodId = $stmt->fetchColumn();
            if (!$methodId) {
                $stmt = $pdo->prepare("INSERT INTO daily_methods (name) VALUES (?)");
                $stmt->execute([$methodName]);
                $methodId = $pdo->lastInsertId();
            }

            // 插入记录
            $stmt = $pdo->prepare("INSERT INTO daily_records (channel_id, platform_id, method_id, price, remark, purchase_date) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$channelId, $platformId, $methodId, (float)$price, $remark, $date]);

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
  <title>导入日常消费记录</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
  <h3 class="mb-4">导入日常消费记录（CSV 文件）</h3>

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
    <a href="daily_index.php" class="btn btn-secondary">返回日常消费</a>
  </form>

  <div class="alert alert-info">
    <strong>CSV 格式要求：</strong><br>
    表头必须为：<code>渠道,平台,方式,金额,备注,日期</code><br>
    示例行：<br>
    京东,微信,招商银行信用卡,18.5,买零食,2025-06-01
  </div>
</div>
</body>
</html>
