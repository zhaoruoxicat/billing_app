<?php
session_start();
require 'auth.php'; // 如果你有权限控制
?>
<?php
require 'db.php';
$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM drink_brands WHERE id = ?");
$stmt->execute([$id]);
$brand = $stmt->fetch();

if (!$brand) exit("品牌不存在");
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="UTF-8">
  <title>编辑品牌</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="theme.css">
</head>
<body class="bg-light">
  <div class="container py-4">
    <h2>编辑品牌</h2>

    <form method="post" action="drink_update_brand.php">
      <input type="hidden" name="id" value="<?= $brand['id'] ?>">
      <div class="mb-3">
        <label class="form-label">品牌名称</label>
        <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($brand['name']) ?>" required>
      </div>
      <button type="submit" class="btn btn-primary">保存</button>
      <a href="drink_brands.php" class="btn btn-secondary">取消</a>
    </form>
  </div>
</body>
</html>
