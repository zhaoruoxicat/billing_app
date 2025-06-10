<?php
session_start();
require 'auth.php'; // 如果你有权限控制
?>

<div class="d-flex justify-content-end align-items-center mb-3" style="margin-top: 0px; margin-right: 20px;">
  <div class="text-end small">
    <span class="me-2">当前用户：<strong><?= htmlspecialchars($_SESSION['username'] ?? '访客') ?></strong></span>
    <a href="change_password.php" class="btn btn-sm btn-outline-secondary me-2">修改用户信息</a>
    <a href="logout.php" class="btn btn-sm btn-outline-danger">退出</a>
  </div>
</div>
