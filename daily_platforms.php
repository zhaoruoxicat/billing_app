<?php
session_start();
require 'auth.php'; // 如果你有权限控制
?>
<?php require 'db.php'; ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="UTF-8">
  <title>支付平台管理</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="theme.css">
</head>
<body class="bg-light">
<div class="container py-4">
  <h2>支付平台管理</h2>

  <form method="post" action="daily_add_platform.php" class="row g-3 mb-4">
    <div class="col-md-6">
      <input type="text" name="name" class="form-control" placeholder="新增平台名称" required>
    </div>
    <div class="col-md-6">
      <button class="btn btn-success">添加</button>
      <a href="daily_index.php" class="btn btn-secondary">返回</a>
    </div>
  </form>

  <table class="table table-bordered table-striped">
    <thead><tr><th>ID</th><th>名称</th><th>操作</th></tr></thead>
    <tbody>
      <?php
      $platforms = $pdo->query("SELECT * FROM daily_platforms ORDER BY id DESC")->fetchAll();
      foreach ($platforms as $platform) {
        echo "<tr>
          <td>{$platform['id']}</td>
          <td>" . htmlspecialchars($platform['name']) . "</td>
          <td>
            <a href=\"daily_edit_platform.php?id={$platform['id']}\" class=\"btn btn-sm btn-outline-primary\">编辑</a>
            <a href=\"daily_delete_platform.php?id={$platform['id']}\" class=\"btn btn-sm btn-outline-danger\" onclick=\"return confirm('确认删除？')\">删除</a>
          </td>
        </tr>";
      }
      ?>
    </tbody>
  </table>
</div>
</body>
</html>
