<?php
session_start();
require 'db.php';
require 'auth.php';

$message = '';
$currentUsername = $_SESSION['username'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newUsername = trim($_POST['new_username']);
    $oldPassword = $_POST['old_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$currentUsername]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($oldPassword, $user['password_hash'])) {
        $message = '原密码不正确';
    } elseif ($newPassword !== '' && $newPassword !== $confirmPassword) {
        $message = '两次新密码不一致';
    } else {
        $updates = [];
        $params = [];

        if ($newUsername !== '' && $newUsername !== $currentUsername) {
            $updates[] = "username = ?";
            $params[] = $newUsername;
        }

        if ($newPassword !== '') {
            $updates[] = "password_hash = ?";
            $params[] = password_hash($newPassword, PASSWORD_DEFAULT);
        }

        if (!empty($updates)) {
            $params[] = $currentUsername;
            $sql = "UPDATE users SET " . implode(', ', $updates) . " WHERE username = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);

            if ($newUsername !== $currentUsername) {
                $_SESSION['username'] = $newUsername;
            }

            $message = '修改成功';
        } else {
            $message = '没有修改任何内容';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="UTF-8">
  <title>修改用户名与密码</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="theme.css">
</head>
<body class="bg-light">
<div class="container py-5">
  <h3 class="mb-4">修改用户名与密码</h3>

  <?php if ($message): ?>
    <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
  <?php endif; ?>

  <div class="row justify-content-center">
    <form method="post" class="col-12 col-md-6">
      <div class="mb-3">
        <label class="form-label">新用户名（留空则不修改）</label>
        <input type="text" name="new_username" class="form-control" placeholder="当前：<?= htmlspecialchars($currentUsername) ?>">
      </div>
      <div class="mb-3">
        <label class="form-label">当前密码（必填）</label>
        <input type="password" name="old_password" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">新密码（可选）</label>
        <input type="password" name="new_password" class="form-control">
      </div>
      <div class="mb-3">
        <label class="form-label">确认新密码</label>
        <input type="password" name="confirm_password" class="form-control">
      </div>
      <div class="d-flex gap-2">
        <button class="btn btn-primary">提交修改</button>
        <a href="index.php" class="btn btn-secondary">返回首页</a>
      </div>
    </form>
  </div>
</div>
</body>
</html>
