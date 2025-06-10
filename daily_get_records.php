<?php
session_start();
require 'auth.php'; // 如果你有权限控制
require 'db.php';

// 查询所有记录，包含关联字段
$stmt = $pdo->query("
  SELECT r.*, 
         c.name AS channel, 
         p.name AS platform, 
         m.name AS method
  FROM daily_records r
  JOIN daily_channels c ON r.channel_id = c.id
  JOIN daily_platforms p ON r.platform_id = p.id
  JOIN daily_methods m ON r.method_id = m.id
  ORDER BY r.purchase_date DESC
");
$records = $stmt->fetchAll();

// 分组汇总
$grouped = [];
foreach ($records as $r) {
    $monthKey = date('Y-m', strtotime($r['purchase_date']));
    if (!isset($grouped[$monthKey])) {
        $grouped[$monthKey] = ['total' => 0, 'count' => 0, 'items' => []];
    }
    $grouped[$monthKey]['total'] += $r['price'];
    $grouped[$monthKey]['count']++;
    $grouped[$monthKey]['items'][] = $r;
}

// 输出表格
krsort($grouped);
foreach ($grouped as $month => $data) {
    $monthLabel = date('Y年n月', strtotime($month));
    $total = number_format($data['total'], 2);
    $count = $data['count'];
    $avg = number_format($data['total'] / $count, 2);

    echo "<tr class='table-secondary fw-bold'>
            <td colspan='7'>{$monthLabel} 合计：{$total} 元，共 {$count} 笔，均价 {$avg} 元</td>
          </tr>";

    foreach ($data['items'] as $r) {
        echo "<tr>
            <td>" . htmlspecialchars($r['channel']) . "</td>
            <td>" . htmlspecialchars($r['platform']) . "</td>
            <td>" . htmlspecialchars($r['method']) . "</td>
            <td>" . number_format($r['price'], 2) . "</td>
            <td>" . htmlspecialchars($r['remark']) . "</td>
            <td>" . htmlspecialchars($r['purchase_date']) . "</td>
            <td>
              <a href=\"daily_edit_record.php?id={$r['id']}\" class=\"btn btn-sm btn-outline-primary\">编辑</a>
              <a href=\"daily_delete_record.php?id={$r['id']}\" class=\"btn btn-sm btn-outline-danger\" onclick=\"return confirm('确认删除？')\">删除</a>
            </td>
          </tr>";
    }
}
