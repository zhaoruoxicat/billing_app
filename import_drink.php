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
            [$brandName, $price, $date] = $data;

            // 插入品牌（若不存在）
            $stmt = $pdo->prepare("SELECT id FROM drink_brands WHERE name = ?");
            $stmt->execute([$brandName]);
            $brandId = $stmt->fetchColumn();

            if (!$brandId) {
                $stmt = $pdo->prepare("INSERT INTO drink_brands (name) VALUES (?)");
                $stmt->execute([$brandName]);
                $brandId = $pdo->lastInsertId();
            }

            // 插入消费记录
            $stmt = $pdo->prepare("INSERT INTO drink_records (brand_id, price, purchase_date) VALUES (?, ?, ?)");
            $stmt->execute([$brandId, (float)$price, $date]);

            $rowCount++;
        }
        fclose($handle);
        $success = "成功导入 $rowCount 条记录";
    } else {
        $error = "无法读取文件";
    }
}
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="UTF-8">
  <title>导入饮品记录</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
  <div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">

      <h3 class="mb-4 text-center">导入饮品记录（CSV）</h3>

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
        <div class="d-grid gap-2 d-md-flex justify-content-md-between">
          <button class="btn btn-primary">上传并导入</button>
          <a href="drink_index.php" class="btn btn-secondary">返回饮品记录</a>
        </div>
      </form>

      <div class="alert alert-info small">
        <strong>CSV 格式要求：</strong><br>
        第一行为表头：品牌,价格,日期<br>
        示例数据：<br>
        喜茶,18.5,2024-12-01
      </div>

    </div>
  </div>
</div>
</body>
</html>