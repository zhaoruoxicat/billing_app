<?php
session_start();
require 'auth.php'; // 如果你有权限控制
?>

<?php
require 'db.php';

// 获取所有年份
$yearRows = $pdo->query("
    SELECT DISTINCT YEAR(purchase_date) AS y FROM (
        SELECT purchase_date FROM drink_records
        UNION ALL
        SELECT purchase_date FROM takeout_records
        UNION ALL
        SELECT purchase_date FROM daily_records
    ) AS all_dates
    ORDER BY y DESC
")->fetchAll(PDO::FETCH_COLUMN);

// 当前年份
$currentYear = date('Y');

// 获取 GET 参数，设置默认值
$selectedYear = $_GET['year'] ?? $currentYear;
$whereClause = $selectedYear === 'all' ? '' : "WHERE YEAR(purchase_date) = $selectedYear";

// 合并模块每月金额
$query = "
    SELECT '饮品' AS category, DATE_FORMAT(purchase_date, '%Y-%m') AS ym, SUM(price) AS total
    FROM drink_records $whereClause
    GROUP BY DATE_FORMAT(purchase_date, '%Y-%m')

    UNION ALL

    SELECT '外卖', DATE_FORMAT(purchase_date, '%Y-%m'), SUM(price)
    FROM takeout_records $whereClause
    GROUP BY DATE_FORMAT(purchase_date, '%Y-%m')

    UNION ALL

    SELECT '日常', DATE_FORMAT(purchase_date, '%Y-%m'), SUM(price)
    FROM daily_records $whereClause
    GROUP BY DATE_FORMAT(purchase_date, '%Y-%m')
";

$rows = $pdo->query($query)->fetchAll();

// 整理每月分类数据
$monthly = [];
foreach ($rows as $row) {
    $ym = $row['ym'];
    $category = $row['category'];
    $total = (float)$row['total'];
    if (!isset($monthly[$ym])) {
        $monthly[$ym] = ['饮品' => 0, '外卖' => 0, '日常' => 0];
    }
    $monthly[$ym][$category] = $total;
}
krsort($monthly);

// 准备图表数据
$chart_labels = [];
$drink_data = [];
$takeout_data = [];
$daily_data = [];

foreach ($monthly as $ym => $data) {
    $chart_labels[] = $ym;
    $drink_data[] = $data['饮品'];
    $takeout_data[] = $data['外卖'];
    $daily_data[] = $data['日常'];
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="UTF-8">
  <title>记账首页</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="icon" href="favicon.ico" type="image/x-icon">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link rel="stylesheet" href="theme.css">
  <link rel="manifest" href="manifest.json">
<meta name="theme-color" content="#007bff">

</head>
<body class="bg-light">
<div class="container py-4">
  <?php include 'header_user.php'; ?>
  <h2 class="mb-4">账单汇总</h2>

  <!-- 快捷入口 -->
  <div class="mb-4 d-flex flex-wrap gap-2">
    <a href="drink_index.php" class="btn btn-primary">饮品记账</a>
    <a href="takeout_index.php" class="btn btn-success">外卖记账</a>
    <a href="daily_index.php" class="btn btn-warning">日常消费记账</a>
    <a href="chart_selector.php" class="btn btn-info">查看图表</a>
  </div>

  <!-- 年份筛选 -->
  <form method="get" class="mb-3">
    <div class="row g-2 align-items-center">
      <div class="col-auto">
        <label for="year" class="col-form-label">筛选年份：</label>
      </div>
      <div class="col-auto">
        <select name="year" id="year" class="form-select" onchange="this.form.submit()">
          <?php foreach ($yearRows as $year): ?>
            <option value="<?= $year ?>" <?= $selectedYear == $year ? 'selected' : '' ?>><?= $year ?> 年</option>
          <?php endforeach; ?>
          <option value="all" <?= $selectedYear === 'all' ? 'selected' : '' ?>>全部年份</option>
        </select>
      </div>
    </div>
  </form>

  <!-- 月度汇总表格 -->
  <table class="table table-bordered table-striped">
    <thead class="table-secondary">
      <tr>
        <th>月份</th>
        <th>饮品合计 (元)</th>
        <th>外卖合计 (元)</th>
        <th>日常合计 (元)</th>
        <th>总计 (元)</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($monthly as $ym => $data): ?>
        <tr>
          <td><?= htmlspecialchars($ym) ?></td>
          <td><?= number_format($data['饮品'], 2) ?></td>
          <td><?= number_format($data['外卖'], 2) ?></td>
          <td><?= number_format($data['日常'], 2) ?></td>
          <td class="fw-bold"><?= number_format($data['饮品'] + $data['外卖'] + $data['日常'], 2) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>


</body>
</html>
