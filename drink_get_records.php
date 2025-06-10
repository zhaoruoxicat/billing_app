<?php
session_start();
require 'auth.php'; // 如果你有权限控制
?>
<?php
require 'db.php';

// 获取所有记录，按日期降序
$stmt = $pdo->query("
    SELECT r.id, r.price, r.purchase_date, b.name AS brand
    FROM drink_records r
    JOIN drink_brands b ON r.brand_id = b.id
    ORDER BY r.purchase_date DESC
");
$records = $stmt->fetchAll();


// 分组处理：按年月分组记录
$grouped = [];
foreach ($records as $r) {
    $monthKey = date('Y-m', strtotime($r['purchase_date']));
    if (!isset($grouped[$monthKey])) {
        $grouped[$monthKey] = ['total' => 0, 'count' => 0, 'items' => []];
    }
    $grouped[$monthKey]['total'] += $r['price'];
    $grouped[$monthKey]['count'] += 1;
    $grouped[$monthKey]['items'][] = $r;
}

// 输出每组记录
foreach ($grouped as $month => $data) {
    $monthText = date('Y年n月', strtotime($month));
    $total = number_format($data['total'], 2);
    $count = $data['count'];
    $avg = $count > 0 ? number_format($data['total'] / $count, 2) : '0.00';

    echo "<tr class='table-secondary fw-bold'>
            <td colspan='4'>{$monthText}合计：{$total} 元，本月共 {$count} 笔，均价 {$avg} 元</td>
          </tr>";

    foreach ($data['items'] as $r) {
        echo "<tr>
            <td>" . htmlspecialchars($r['brand']) . "</td>
            <td>" . number_format($r['price'], 2) . "</td>
            <td>" . htmlspecialchars($r['purchase_date']) . "</td>
            <td>
                <a href=\"drink_edit_record.php?id={$r['id']}\" class=\"btn btn-sm btn-outline-primary\">编辑</a>
                <a href=\"drink_delete_record.php?id={$r['id']}\" class=\"btn btn-sm btn-outline-danger\" onclick=\"return confirm('确认删除此记录？');\">删除</a>
            </td>
        </tr>";
    }
}
