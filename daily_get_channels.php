<?php
session_start();
require 'auth.php'; // 如果你有权限控制
require 'db.php';
$stmt = $pdo->query("SELECT id, name FROM daily_channels ORDER BY name ASC");
foreach ($stmt as $row) {
  echo "<option value=\"{$row['id']}\">" . htmlspecialchars($row['name']) . "</option>";
}
