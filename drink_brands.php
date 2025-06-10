<?php
session_start();
require 'auth.php'; // 如果你有权限控制
?>
<?php
require 'db.php';
$brands = $pdo->query("SELECT * FROM drink_brands ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="UTF-8">
  <title>品牌管理</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="theme.css">
</head>
<body class="bg-light">
  <div class="container py-4">
    <h2 class="mb-4">品牌管理</h2>

    <!-- 添加新品牌 -->
    <form method="post" action="drink_add_brand.php" class="row g-3 mb-4">
      <div class="col-md-6">
        <input type="text" name="name" class="form-control" placeholder="请输入新品牌名称" required>
      </div>
      <div class="col-md-6">
        <button type="submit" class="btn btn-success">添加品牌</button>
        <a href="drink_index.php" class="btn btn-secondary">返回</a>
      </div>
    </form>

    <!-- 品牌列表 -->
    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>ID</th>
          <th>品牌名称</th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($brands as $brand): ?>
        <tr>
          <td><?= $brand['id'] ?></td>
          <td><?= htmlspecialchars($brand['name']) ?></td>
          <td>
            <a href="drink_edit_brand.php?id=<?= $brand['id'] ?>" class="btn btn-sm btn-outline-primary">编辑</a>
            <a href="drink_delete_brand.php?id=<?= $brand['id'] ?>" class="btn btn-sm btn-outline-danger"
               onclick="return confirm('确认删除该品牌？相关消费记录也将被删除');">删除</a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
