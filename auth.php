<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'db.php';

// 如果已登录，则无需处理
if (isset($_SESSION['user_id'])) {
    return;
}

// 避免在 login.php 中触发重定向
$currentPage = basename($_SERVER['PHP_SELF']);
if ($currentPage === 'login.php') {
    return;
}

// 尝试通过 Cookie 自动登录
if (isset($_COOKIE['remember_token'])) {
    $token = $_COOKIE['remember_token'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE remember_token = ?");
    $stmt->execute([$token]);
    $user = $stmt->fetch();

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        return;
    }
}

// 未登录，跳转登录页面
header("Location: login.php");
exit;
