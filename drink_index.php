<?php
session_start();
require 'auth.php'; // 如果你有权限控制
?>

<?php require 'db.php';

// 当前年月默认值
$currentYear = date('Y');
$currentMonth = date('m');

// 获取 GET 参数
$selectedYear = $_GET['year'] ?? $currentYear;
$selectedMonth = $_GET['month'] ?? $currentMonth;

// 获取年份列表
$yearList = $pdo->query("SELECT DISTINCT YEAR(purchase_date) AS y FROM drink_records ORDER BY y DESC")->fetchAll(PDO::FETCH_COLUMN);
$monthList = range(1, 12);

// 构造查询条件
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

$whereClause = $whereSQL !== '' ? "WHERE $whereSQL" : '';
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="UTF-8">
  <title>饮品消费记录</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="theme.css">
</head>
<body class="bg-light">
  <div class="container py-4">
    <?php include 'header_user.php'; ?>

    <h2 class="mb-4">饮品消费记录</h2>
    <h4 class="mb-4">按时间筛选</h4>
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
          <?php foreach ($monthList as $m): 
            $mVal = str_pad($m, 2, '0', STR_PAD_LEFT); ?>
            <option value="<?= $mVal ?>" <?= $mVal == $selectedMonth ? 'selected' : '' ?>><?= $m ?> 月</option>
          <?php endforeach; ?>
        </select>
      </div>
    </form>
    <h4 class="mb-4">添加记录</h4>
    <!-- 添加表单 -->
    <form class="row g-3 mb-4" method="post" action="drink_add_record.php">
      <div class="col-md-4">
        <label for="brand" class="form-label">品牌</label>
        <select class="form-select" id="brand" name="brand_id" required>
          <?php include 'drink_get_brands.php'; ?>
        </select>
      </div>
      <div class="col-md-4">
        <label for="price" class="form-label">价格（元）</label>
        <input type="number" class="form-control" id="price" name="price" step="0.01" required>
      </div>
      <div class="col-md-4">
        <label for="date" class="form-label">日期</label>
        <input type="date" class="form-control" id="date" name="purchase_date" required>
      </div>
    <div class="col-12 d-flex flex-wrap gap-2">
      <button type="submit" class="btn btn-primary">添加记录</button>
      <a href="index.php" class="btn btn-outline-secondary">返回首页</a>
    
      <!-- 强制换行并添加上下间距 -->
      <div class="w-100 my-2"></div>
    
      <a href="drink_brands.php" class="btn btn-secondary">管理品牌</a>
      <a href="import_drink.php" class="btn btn-outline-secondary">导入数据</a>
    </div>

    </form>

    <!-- 历史记录 -->
    <h4>
      <?php
        if ($selectedYear === 'all' && $selectedMonth === 'all') {
          echo "全部记录";
        } elseif ($selectedYear !== 'all' && $selectedMonth === 'all') {
          echo "{$selectedYear} 年全部记录";
        } elseif ($selectedYear === 'all' && $selectedMonth !== 'all') {
          echo "{$selectedMonth} 月全部年份记录";
        } else {
          echo "{$selectedYear} 年 {$selectedMonth} 月记录";
        }
      ?>
    </h4>

    <table class="table table-bordered">
      <thead>
        <tr>
          <th>品牌</th>
          <th>价格</th>
          <th>日期</th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
        <?php
        // 查询数据
        $sql = "
            SELECT r.id, r.price, r.purchase_date, b.name AS brand
            FROM drink_records r
            JOIN drink_brands b ON r.brand_id = b.id
            $whereClause
            ORDER BY r.purchase_date DESC
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $records = $stmt->fetchAll();

        // 分组按年月
        $grouped = [];
        foreach ($records as $r) {
            $key = date('Y-m', strtotime($r['purchase_date']));
            $grouped[$key][] = $r;
        }

        // 背景色数组（循环使用）
        $bgClasses = [
            'table-light', 'table-secondary', 'table-info', 'table-warning',
            'table-success', 'table-danger', 'table-primary', 'table-dark',
            'table-active', 'table-light', 'table-secondary', 'table-info'
        ];

        $index = 0;
        foreach ($grouped as $month => $items):
            $bg = $bgClasses[$index % count($bgClasses)];
            $index++;
            $monthText = date('Y年n月', strtotime($month));
            $monthTotal = array_sum(array_column($items, 'price'));
            $monthCount = count($items);
            $monthAvg = $monthCount > 0 ? number_format($monthTotal / $monthCount, 2) : '0.00';
        ?>
        <tr class="<?= $bg ?> fw-bold">
          <td colspan="4"><?= $monthText ?> 合计：<?= number_format($monthTotal, 2) ?> 元，均价 <?= $monthAvg ?> 元</td>
        </tr>
        <?php foreach ($items as $r): ?>
        <tr class="<?= $bg ?>">
          <td><?= htmlspecialchars($r['brand']) ?></td>
          <td><?= number_format($r['price'], 2) ?></td>
          <td><?= htmlspecialchars($r['purchase_date']) ?></td>
          <td>
            <a href="drink_edit_record.php?id=<?= $r['id'] ?>" class="btn btn-sm btn-outline-primary">编辑</a>
            <a href="drink_delete_record.php?id=<?= $r['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('确认删除此记录？');">删除</a>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php endforeach; ?>

        <?php if (count($records) === 0): ?>
        <tr><td colspan="4" class="text-center text-muted">暂无记录</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
