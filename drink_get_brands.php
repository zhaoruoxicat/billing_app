<?php
session_start();
require 'auth.php'; // 如果你有权限控制
?>
<?php
require 'db.php';

$stmt = $pdo->query("SELECT id, name FROM drink_brands ORDER BY name ASC");
$brands = $stmt->fetchAll();

foreach ($brands as $brand) {
    echo "<option value=\"{$brand['id']}\">" . htmlspecialchars($brand['name']) . "</option>";
}
