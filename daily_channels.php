<?php
session_start();
require 'auth.php'; // 如果你有权限控制
?>
<?php require 'db.php'; ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="UTF-8">
  <title>消费渠道管理</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="theme.css">
</head>
<body class="bg-light">
<div class="container py-4">
  <h2>消费渠道管理</h2>

  <!-- 添加渠道 -->
  <form method="post" action="daily_add_channel.php" class="row g-3 mb-4">
    <div class="col-md-6">
      <input type="text" name="name" class="form-control" placeholder="新增渠道名称" required>
    </div>
    <div class="col-md-6">
      <button class="btn btn-success">添加</button>
      <a href="daily_index.php" class="btn btn-secondary">返回</a>
    </div>
  </form>

  <!-- 渠道列表 -->
  <table class="table table-bordered table-striped">
    <thead><tr><th>ID</th><th>名称</th><th>操作</th></tr></thead>
    <tbody>
      <?php
      $channels = $pdo->query("SELECT * FROM daily_channels ORDER BY id DESC")->fetchAll();
      foreach ($channels as $channel) {
        echo "<tr>
          <td>{$channel['id']}</td>
          <td>" . htmlspecialchars($channel['name']) . "</td>
          <td>
            <a href=\"daily_edit_channel.php?id={$channel['id']}\" class=\"btn btn-sm btn-outline-primary\">编辑</a>
            <a href=\"daily_delete_channel.php?id={$channel['id']}\" class=\"btn btn-sm btn-outline-danger\" onclick=\"return confirm('确认删除？')\">删除</a>
          </td>
        </tr>";
      }
      ?>
    </tbody>
  </table>
</div>
</body>
</html>
