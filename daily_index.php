<?php
session_start();
require 'auth.php';
require 'db.php';

$currentYear = date('Y');
$currentMonth = date('m');

// 获取筛选参数
$selectedYear = $_GET['year'] ?? $currentYear;
$selectedMonth = $_GET['month'] ?? $currentMonth;

// 获取年份列表
$yearList = $pdo->query("SELECT DISTINCT YEAR(purchase_date) AS y FROM daily_records ORDER BY y DESC")
                ->fetchAll(PDO::FETCH_COLUMN);

// 构造 WHERE 子句
$whereSQL = [];
$params = [];

if ($selectedYear !== 'all') {
    $whereSQL[] = "YEAR(r.purchase_date) = ?";
    $params[] = (int)$selectedYear;
}
if ($selectedMonth !== 'all') {
    $whereSQL[] = "MONTH(r.purchase_date) = ?";
    $params[] = (int)$selectedMonth;
}

$whereClause = '';
if (!empty($whereSQL)) {
    $whereClause = "WHERE " . implode(" AND ", $whereSQL);
}

// 查询记录
$stmt = $pdo->prepare("
    SELECT r.*, ch.name AS channel, pf.name AS platform, pm.name AS method
    FROM daily_records r
    LEFT JOIN daily_channels ch ON r.channel_id = ch.id
    LEFT JOIN daily_platforms pf ON r.platform_id = pf.id
    LEFT JOIN daily_methods pm ON r.method_id = pm.id
    $whereClause
    ORDER BY r.purchase_date DESC
");
$stmt->execute($params);
$records = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="UTF-8">
  <title>日常消费记录</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="theme.css">
</head>
<body class="bg-light">
<div class="container py-4">
  <?php include 'header_user.php'; ?>

  <h2 class="mb-4">日常消费记录</h2>

  <!-- 筛选 -->
  <form method="get" class="row g-2 mb-4">
    <div class="col-md-3">
      <select name="year" class="form-select" onchange="this.form.submit()">
        <option value="all" <?= $selectedYear === 'all' ? 'selected' : '' ?>>全部年份</option>
        <?php foreach ($yearList as $y): ?>
          <option value="<?= $y ?>" <?= $y == $selectedYear ? 'selected' : '' ?>><?= $y ?> 年</option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-3">
      <select name="month" class="form-select" onchange="this.form.submit()">
        <option value="all" <?= $selectedMonth === 'all' ? 'selected' : '' ?>>全部月份</option>
        <?php for ($m = 1; $m <= 12; $m++): 
          $val = str_pad($m, 2, '0', STR_PAD_LEFT); ?>
          <option value="<?= $val ?>" <?= $val == $selectedMonth ? 'selected' : '' ?>><?= $m ?> 月</option>
        <?php endfor; ?>
      </select>
    </div>
  </form>

  <!-- 添加记录 -->
  <form method="post" action="daily_add_record.php" class="row g-3 mb-4">
    <div class="col-md-3">
      <label class="form-label">渠道</label>
      <select name="channel_id" class="form-select" required>
        <?php include 'daily_get_channels.php'; ?>
      </select>
    </div>
    <div class="col-md-3">
      <label class="form-label">支付平台</label>
      <select name="platform_id" class="form-select" required>
        <?php include 'daily_get_platforms.php'; ?>
      </select>
    </div>
    <div class="col-md-3">
      <label class="form-label">支付方式</label>
      <select name="method_id" class="form-select" required>
        <?php include 'daily_get_methods.php'; ?>
      </select>
    </div>
    <div class="col-md-3">
      <label class="form-label">金额</label>
      <input type="number" name="price" step="0.01" class="form-control" required>
    </div>
    <div class="col-md-6">
      <label class="form-label">备注</label>
      <input type="text" name="remark" class="form-control">
    </div>
    <div class="col-md-3">
      <label class="form-label">日期</label>
      <input type="date" name="purchase_date" class="form-control" required>
    </div>
    <div class="col-12 d-flex flex-column gap-2">
      <div class="d-flex gap-2">
        <button class="btn btn-primary">添加记录</button>
        <a href="index.php" class="btn btn-outline-secondary">返回首页</a>
      </div>
      <div class="w-100 my-2"></div>
      <div class="d-flex gap-2">
        <a href="daily_channels.php" class="btn btn-outline-secondary">管理渠道</a>
        <a href="daily_platforms.php" class="btn btn-outline-secondary">管理平台</a>
        <a href="daily_methods.php" class="btn btn-outline-secondary">管理方式</a>
        <a href="import_daily.php" class="btn btn-outline-secondary">导入数据</a>
      </div>
    </div>
    

  </form>

  <!-- 历史记录 -->
  <h4 class="mb-3">消费历史</h4>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>渠道</th>
        <th>支付平台</th>
        <th>支付方式</th>
        <th>金额</th>
        <th>备注</th>
        <th>日期</th>
        <th>操作</th>
      </tr>
    </thead>
    <tbody>
<?php
$grouped = [];
foreach ($records as $r) {
    $monthKey = date('Y-m', strtotime($r['purchase_date']));
    $grouped[$monthKey][] = $r;
}

$colors = ['table-light', 'table-secondary', 'table-info', 'table-warning', 'table-success', 'table-danger'];
$i = 0;

foreach ($grouped as $month => $items):
    $color = $colors[$i++ % count($colors)];
    $sum = array_sum(array_column($items, 'price'));
    $avg = count($items) > 0 ? number_format($sum / count($items), 2) : '0.00';
    $total = number_format($sum, 2);
?>
  <tr class="<?= $color ?> fw-bold">
    <td colspan="7"><?= date('Y年n月', strtotime($month)) ?> 合计：<?= $total ?> 元，均价 <?= $avg ?> 元</td>
  </tr>
  <?php foreach ($items as $r): ?>
  <tr class="<?= $color ?>">
    <td><?= htmlspecialchars($r['channel']) ?></td>
    <td><?= htmlspecialchars($r['platform']) ?></td>
    <td><?= htmlspecialchars($r['method']) ?></td>
    <td><?= number_format($r['price'], 2) ?></td>
    <td><?= htmlspecialchars($r['remark']) ?></td>
    <td><?= htmlspecialchars($r['purchase_date']) ?></td>
    <td>
      <a href="daily_edit_record.php?id=<?= $r['id'] ?>" class="btn btn-sm btn-outline-primary">编辑</a>
      <a href="daily_delete_record.php?id=<?= $r['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('确定删除此记录？')">删除</a>
    </td>
  </tr>
  <?php endforeach; ?>
<?php endforeach; ?>

<?php if (count($records) === 0): ?>
  <tr><td colspan="7" class="text-center text-muted">暂无记录</td></tr>
<?php endif; ?>
    </tbody>
  </table>
</div>
</body>
</html>
