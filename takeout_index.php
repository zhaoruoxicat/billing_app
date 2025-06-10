<?php
session_start();
require 'auth.php'; // 如果你有权限控制
?>

<?php require 'db.php';

// 默认年月
$currentYear = date('Y');
$currentMonth = date('m');

// 获取筛选参数
$selectedYear = $_GET['year'] ?? $currentYear;
$selectedMonth = $_GET['month'] ?? $currentMonth;

// 获取年份列表（修复 ORDER BY 问题）
$yearList = $pdo->query("SELECT DISTINCT YEAR(purchase_date) AS y FROM takeout_records ORDER BY y DESC")
                ->fetchAll(PDO::FETCH_COLUMN);

// 构造 WHERE 语句
$whereSQL = '';
$params = [];

if ($selectedYear !== 'all') {
    $whereSQL .= "YEAR(r.purchase_date) = ?";
    $params[] = (int)$selectedYear;
}
if ($selectedMonth !== 'all') {
    if ($whereSQL !== '') $whereSQL .= " AND ";
    $whereSQL .= "MONTH(r.purchase_date) = ?";
    $params[] = (int)$selectedMonth;
}
$whereClause = $whereSQL ? "WHERE $whereSQL" : '';
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="UTF-8">
  <title>外卖消费记录</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="theme.css">
</head>
<body class="bg-light">
<div class="container py-4">
  <?php include 'header_user.php'; ?>

  <h2 class="mb-4">外卖消费记录</h2>
  <h3 class="mb-4">按时间筛选</h3>
  <!-- 年月筛选 -->
  <form method="get" class="row g-2 mb-3">
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
  <h3 class="mb-4">添加记录</h3>
  <form method="post" action="takeout_add_record.php" class="row g-3 mb-4">
    <div class="col-md-3">
      <label class="form-label">店铺</label>
      <select name="shop_id" class="form-select" required>
        <?php include 'takeout_get_shops.php'; ?>
      </select>
    </div>
    <div class="col-md-3">
      <label class="form-label">渠道</label>
      <select name="channel_id" class="form-select" required>
        <?php include 'takeout_get_channels.php'; ?>
      </select>
    </div>
    <div class="col-md-3">
      <label class="form-label">金额</label>
      <input type="number" name="price" step="0.01" class="form-control" required>
    </div>
    <div class="col-md-3">
      <label class="form-label">日期</label>
      <input type="date" name="purchase_date" class="form-control" required>
    </div>
<div class="col-12 d-flex flex-wrap gap-2">
  <button class="btn btn-primary">添加记录</button>
  <a href="index.php" class="btn btn-outline-secondary">返回首页</a>

  <!-- 换行 + 上下间距 -->
  <div class="w-100 my-2"></div>

  <a href="takeout_shops.php" class="btn btn-outline-secondary">管理店铺</a>
  <a href="takeout_channels.php" class="btn btn-outline-secondary">管理渠道</a>
  <a href="import_takeout.php" class="btn btn-outline-secondary">导入数据</a>
</div>

  </form>

  <!-- 历史记录 -->
  <h4>
    <?php
    if ($selectedYear === 'all' && $selectedMonth === 'all') echo "全部记录";
    elseif ($selectedMonth === 'all') echo "{$selectedYear} 年全部记录";
    elseif ($selectedYear === 'all') echo "{$selectedMonth} 月所有年份记录";
    else echo "{$selectedYear} 年 {$selectedMonth} 月记录";
    ?>
  </h4>

  <table class="table table-bordered">
    <thead>
      <tr>
        <th>店铺</th>
        <th>渠道</th>
        <th>金额</th>
        <th>日期</th>
        <th>操作</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $stmt = $pdo->prepare("
        SELECT r.*, s.name AS shop, c.name AS channel
        FROM takeout_records r
        JOIN takeout_shops s ON r.shop_id = s.id
        JOIN takeout_channels c ON r.channel_id = c.id
        $whereClause
        ORDER BY r.purchase_date DESC
      ");
      $stmt->execute($params);
      $records = $stmt->fetchAll();

      $grouped = [];
      foreach ($records as $r) {
        $key = date('Y-m', strtotime($r['purchase_date']));
        $grouped[$key][] = $r;
      }

      $bgColors = [
        'table-light', 'table-secondary', 'table-info', 'table-warning',
        'table-success', 'table-danger', 'table-primary', 'table-dark',
        'table-active', 'table-light', 'table-secondary', 'table-info'
      ];

      $i = 0;
      foreach ($grouped as $month => $items):
        $bg = $bgColors[$i % 12];
        $i++;
        $monthText = date('Y年n月', strtotime($month));
        $sum = array_sum(array_column($items, 'price'));
        $total = number_format($sum, 2);
        $count = count($items);
        $avg = $count > 0 ? number_format($sum / $count, 2) : '0.00';
      ?>
      <tr class="<?= $bg ?> fw-bold">
        <td colspan="5"><?= $monthText ?> 合计：<?= $total ?> 元，均价 <?= $avg ?> 元</td>
      </tr>
      <?php foreach ($items as $r): ?>
      <tr class="<?= $bg ?>">
        <td><?= htmlspecialchars($r['shop']) ?></td>
        <td><?= htmlspecialchars($r['channel']) ?></td>
        <td><?= number_format($r['price'], 2) ?></td>
        <td><?= htmlspecialchars($r['purchase_date']) ?></td>
        <td>
          <a href="takeout_edit_record.php?id=<?= $r['id'] ?>" class="btn btn-sm btn-outline-primary">编辑</a>
          <a href="takeout_delete_record.php?id=<?= $r['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('确定删除此记录？')">删除</a>
        </td>
      </tr>
      <?php endforeach; ?>
      <?php endforeach; ?>

      <?php if (count($records) === 0): ?>
        <tr><td colspan="5" class="text-center text-muted">暂无记录</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
</body>
</html>
