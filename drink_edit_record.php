<?php
session_start();
require 'auth.php'; // 如果你有权限控制
?>
<?php
require 'db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $pdo->prepare("SELECT * FROM drink_records WHERE id = ?");
$stmt->execute([$id]);
$record = $stmt->fetch();

if (!$record) {
    exit("记录不存在");
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="UTF-8">
  <title>编辑消费记录</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="theme.css">
</head>
<body class="bg-light">
  <div class="container py-4">
    <h2 class="mb-4">编辑消费记录</h2>

    <form method="post" action="drink_update_record.php">
      <input type="hidden" name="id" value="<?= $record['id'] ?>">

      <div class="mb-3">
        <label class="form-label">品牌</label>
        <select name="brand_id" class="form-select" required>
          <?php
            $brands = $pdo->query("SELECT id, name FROM drink_brands ORDER BY name ASC")->fetchAll();
            foreach ($brands as $brand) {
                $selected = $brand['id'] == $record['brand_id'] ? 'selected' : '';
                echo "<option value=\"{$brand['id']}\" $selected>{$brand['name']}</option>";
            }
          ?>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">价格（元）</label>
        <input type="number" step="0.01" name="price" class="form-control" value="<?= $record['price'] ?>" required>
      </div>

      <div class="mb-3">
        <label class="form-label">日期</label>
        <input type="date" name="purchase_date" class="form-control" value="<?= $record['purchase_date'] ?>" required>
      </div>

      <button type="submit" class="btn btn-primary">保存修改</button>
      <a href="drink_index.php" class="btn btn-secondary">取消</a>
    </form>
  </div>
</body>
</html>
