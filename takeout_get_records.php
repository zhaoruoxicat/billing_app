<?php
session_start();
require 'auth.php'; // 如果你有权限控制
?>
<?php
$stmt = $pdo->query("
  SELECT r.id, r.price, r.purchase_date, 
         s.name AS shop, 
         c.name AS channel
  FROM takeout_records r
  JOIN takeout_shops s ON r.shop_id = s.id
  JOIN takeout_channels c ON r.channel_id = c.id
  ORDER BY r.purchase_date DESC
");
$records = $stmt->fetchAll();

$grouped = [];
foreach ($records as $r) {
    $month = date('Y-m', strtotime($r['purchase_date']));
    if (!isset($grouped[$month])) {
        $grouped[$month] = ['total' => 0, 'count' => 0, 'items' => []];
    }
    $grouped[$month]['total'] += $r['price'];
    $grouped[$month]['count']++;
    $grouped[$month]['items'][] = $r;
}

krsort($grouped);
foreach ($grouped as $month => $data) {
    $avg = number_format($data['total'] / $data['count'], 2);
    echo "<tr class='table-secondary fw-bold'><td colspan='5'>" . date('Y年n月', strtotime($month)) .
         " 合计：" . number_format($data['total'], 2) .
         " 元，共 {$data['count']} 笔，均价 {$avg} 元</td></tr>";

    foreach ($data['items'] as $r) {
        echo "<tr>
            <td>" . htmlspecialchars($r['shop']) . "</td>
            <td>" . htmlspecialchars($r['channel']) . "</td>
            <td>" . number_format($r['price'], 2) . "</td>
            <td>" . htmlspecialchars($r['purchase_date']) . "</td>
            <td>
              <a href=\"takeout_edit_record.php?id={$r['id']}\" class=\"btn btn-sm btn-outline-primary\">编辑</a>
              <a href=\"takeout_delete_record.php?id={$r['id']}\" class=\"btn btn-sm btn-outline-danger\" onclick=\"return confirm('确认删除？')\">删除</a>
            </td>
        </tr>";
    }
}
