<?php
session_start();
require 'db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        // 登录成功，设置 SESSION
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        // 生成 remember_token
        $token = bin2hex(random_bytes(32));
        $stmt = $pdo->prepare("UPDATE users SET remember_token = ? WHERE id = ?");
        $stmt->execute([$token, $user['id']]);

        // 设置 cookie，30天有效
        setcookie('remember_token', $token, time() + (30 * 24 * 60 * 60), '/');

        header("Location: index.php");
        exit;
    } else {
        $error = '账号或密码错误';
    }
}
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="UTF-8">
  <title>登录</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .login-card {
      max-width: 400px;
      width: 100%;
      padding: 2rem;
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.05);
    }

    @media (prefers-color-scheme: dark) {
      body {
        background-color: #121212;
        color: #fff;
      }
      .login-card {
        background-color: #1e1e1e;
        color: #fff;
      }
      .form-control {
        background-color: #2a2a2a;
        color: #fff;
        border-color: #444;
      }
    }
  </style>
</head>
<body>
  <div class="login-card">
    <h4 class="text-center mb-4">用户登录</h4>
    <?php if (!empty($error)): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="post">
      <div class="mb-3">
        <label class="form-label">账号</label>
        <input type="text" name="username" class="form-control" required autofocus>
      </div>
      <div class="mb-3">
        <label class="form-label">密码</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <button class="btn btn-primary w-100">登录</button>
    </form>
  </div>
</body>
</html>
