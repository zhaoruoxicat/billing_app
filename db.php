
<?php
$host = 'localhost';
$db   = 'sql';  // 数据库名称
$user = 'sql';
$pass = '123456';             // 如果你有密码请填写
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    exit('数据库连接失败: ' . $e->getMessage());
}

