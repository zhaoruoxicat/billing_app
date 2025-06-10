<?php
require 'db.php';
require 'auth.php'; // 如果你有权限控制

header('Content-Type: application/json');

// 获取筛选参数
$selectedSources = $_GET['source'] ?? [];
if (!is_array($selectedSources)) {
    $selectedSources = [$selectedSources];
}
$year = $_GET['year'] ?? 'all';
$month = $_GET['month'] ?? 'all';

// 定义来源映射
$sourceMap = [
    'drink' => ['table' => 'drink_records', 'label' => '饮品'],
    'takeout' => ['table' => 'takeout_records', 'label' => '外卖'],
    'daily' => ['table' => 'daily_records', 'label' => '日常']
];

// 初始化结果容器
$monthlyData = []; // ['2024-06' => ['饮品' => 0, '外卖' => 0, '日常' => 0]]

// 遍历每个来源，查询数据
foreach ($selectedSources as $key) {
    if (!isset($sourceMap[$key])) continue;

    $table = $sourceMap[$key]['table'];
    $label = $sourceMap[$key]['label'];

    $sql = "SELECT DATE_FORMAT(purchase_date, '%Y-%m') AS ym, SUM(price) AS total FROM $table WHERE 1";
    $params = [];

    if ($year !== 'all') {
        $sql .= " AND YEAR(purchase_date) = ?";
        $params[] = $year;
    }

    if ($month !== 'all') {
        $sql .= " AND MONTH(purchase_date) = ?";
        $params[] = (int)$month;
    }

    $sql .= " GROUP BY ym ORDER BY ym";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $results = $stmt->fetchAll(PDO::FETCH_KEY_PAIR); // ym => total

    foreach ($results as $ym => $total) {
        if (!isset($monthlyData[$ym])) {
            $monthlyData[$ym] = ['饮品' => 0, '外卖' => 0, '日常' => 0];
        }
        $monthlyData[$ym][$label] = (float)$total;
    }
}

// 排序月份
ksort($monthlyData);
$labels = array_keys($monthlyData);

// 构造 datasets
$datasets = [];
foreach ($sourceMap as $key => $info) {
    if (!in_array($key, $selectedSources)) continue;

    $datasets[] = [
        'label' => $info['label'],
        'data' => array_map(fn($row) => $row[$info['label']] ?? 0, $monthlyData),
        'backgroundColor' => match ($info['label']) {
            '饮品' => 'rgba(235, 193, 54, 0.7)',
            '外卖' => 'rgba(38, 124, 223, 0.7)',
            '日常' => 'rgba(239, 64, 255, 0.7)',
            default => 'rgba(100, 100, 100, 0.5)'
        }
    ];
}

// 返回 JSON
echo json_encode([
    'labels' => $labels,
    'datasets' => $datasets
]);
