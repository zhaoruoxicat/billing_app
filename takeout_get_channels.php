<?php
session_start();
require 'auth.php'; // 如果你有权限控制
?>
<?php
$stmt = $pdo->query("SELECT id, name FROM takeout_channels ORDER BY name ASC");
foreach ($stmt as $row) {
  echo "<option value=\"{$row['id']}\">" . htmlspecialchars($row['name']) . "</option>";
}
