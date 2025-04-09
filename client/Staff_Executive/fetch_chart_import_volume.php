<?php
include '../../config/connect_db.php';
header('Content-Type: application/json');

$start_date = $_POST['start_date'] ?? null;
$end_date = $_POST['end_date'] ?? null;
$menu_ids = $_POST['menu_ids'] ?? [];

$conditions = [];
if ($start_date && $end_date) {
    $conditions[] = "ir.create_at BETWEEN '$start_date 00:00:00' AND '$end_date 23:59:59'";
}
if (!empty($menu_ids)) {
    $menu_ids_safe = implode(",", array_map('intval', $menu_ids));
    $conditions[] = "ir.menu_id IN ($menu_ids_safe)";
}

$where_sql = '';
if (!empty($conditions)) {
    $where_sql = 'WHERE ' . implode(' AND ', $conditions);
}

// ดึงข้อมูล volume ตามวัตถุดิบ + วันที่
$sql = "
    SELECT 
        DATE(ir.create_at) AS date,
        r.item_name AS ingredient_name,
        SUM(ir.quantity * r.quanity) AS total
    FROM import_raw_material ir
    JOIN menu m ON ir.menu_id = m.menu_id
    JOIN raw_material r ON m.raw_material_id = r.raw_material_id
    $where_sql
    GROUP BY ingredient_name, date
    ORDER BY date ASC;
";

$result = mysqli_query($conn, $sql);

// จัดข้อมูลให้เหมาะกับ Chart.js
$chartData = [];
$labelSet = [];

while ($row = mysqli_fetch_assoc($result)) {
    $date = $row['date'];
    $ingredient = $row['ingredient_name'];
    $total = (float) $row['total'];

    $labelSet[$date] = true;
    $chartData[$ingredient][$date] = $total;
}

$labels = array_keys($labelSet);
sort($labels);

$datasets = [];
foreach ($chartData as $ingredient => $dataPerDate) {
    $data = [];
    foreach ($labels as $label) {
        $data[] = $dataPerDate[$label] ?? 0;
    }
    $datasets[] = [
        'label' => $ingredient,
        'data' => $data
    ];
}

echo json_encode([
    'labels' => $labels,
    'datasets' => $datasets
]);
