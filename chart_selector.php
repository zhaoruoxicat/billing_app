<?php
session_start();
require 'auth.php';
require 'db.php';

// 获取年份列表（从三张表中获取最大年份集合）
$years = [];
foreach (['drink_records', 'takeout_records', 'daily_records'] as $table) {
    $stmt = $pdo->query("SELECT DISTINCT YEAR(purchase_date) AS y FROM $table ORDER BY y DESC");
    $years = array_unique(array_merge($years, $stmt->fetchAll(PDO::FETCH_COLUMN)));
}
rsort($years);

// 获取筛选参数
$selectedSources = $_GET['source'] ?? ['drink', 'takeout', 'daily'];
$selectedYear = $_GET['year'] ?? date('Y');
$selectedMonth = $_GET['month'] ?? 'all';
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="UTF-8">
  <title>消费图表分析</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-light">
<div class="container py-4">
  <h3 class="mb-4">消费图表分析</h3>
  <form method="get" class="row g-2 mb-4">
    <div class="col-md-3">
      <label class="form-label">数据来源</label>
      <?php foreach (['drink' => '饮品', 'takeout' => '外卖', 'daily' => '日常'] as $val => $label): ?>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="source[]" value="<?= $val ?>" id="src_<?= $val ?>"
                 <?= in_array($val, $selectedSources) ? 'checked' : '' ?>>
          <label class="form-check-label" for="src_<?= $val ?>"><?= $label ?></label>
        </div>
      <?php endforeach; ?>
    </div>
    <div class="col-md-3">
      <label class="form-label">年份</label>
      <select name="year" class="form-select">
        <option value="all" <?= $selectedYear === 'all' ? 'selected' : '' ?>>全部</option>
        <?php foreach ($years as $y): ?>
          <option value="<?= $y ?>" <?= $selectedYear == $y ? 'selected' : '' ?>><?= $y ?> 年</option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-3">
      <label class="form-label">月份</label>
      <select name="month" class="form-select">
        <option value="all" <?= $selectedMonth === 'all' ? 'selected' : '' ?>>全部</option>
        <?php for ($m = 1; $m <= 12; $m++): $v = str_pad($m, 2, '0', STR_PAD_LEFT); ?>
          <option value="<?= $v ?>" <?= $selectedMonth == $v ? 'selected' : '' ?>><?= $m ?> 月</option>
        <?php endfor; ?>
      </select>
    </div>
<div class="col-md-3 d-flex align-items-end">
  <button class="btn btn-primary w-50">生成图表</button>
  <div class="mx-1"></div>
  <a href="index.php" class="btn btn-outline-secondary w-50">返回首页</a>
</div>

  </form>

  <canvas id="expenseChart" height="120"></canvas>
</div>

<script>
const ctx = document.getElementById('expenseChart').getContext('2d');
fetch('chart_data_api.php?<?= http_build_query($_GET) ?>')
  .then(res => res.json())
  .then(data => {
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: data.labels,
        datasets: data.datasets
      },
      options: {
        responsive: true,
        plugins: {
          legend: { position: 'top' },
          title: { display: true, text: '消费分类柱状图' }
        },
        scales: {
          x: { stacked: true },
          y: { beginAtZero: true, stacked: true }
        }
      }
    });
  });
</script>
</body>
</html>
