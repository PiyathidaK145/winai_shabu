<?php
include '../../config/connect_db.php';
header('Content-Type: application/json');

// รับค่าจาก POST
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

// SQL: ค่าใช้จ่ายแยกตามวัตถุดิบและวัน
$sql = "
    SELECT 
        DATE(ir.create_at) AS date,
        r.item_name AS ingredient,
        SUM(ir.cost) AS total_cost
    FROM import_raw_material ir
    JOIN menu m ON ir.menu_id = m.menu_id
    JOIN raw_material r ON m.raw_material_id = r.raw_material_id
    $where_sql
    GROUP BY date, ingredient
    ORDER BY date ASC
";

$result = mysqli_query($conn, $sql);

$raw_data = [];
$label_set = [];

while ($row = mysqli_fetch_assoc($result)) {
    $date = $row['date'];
    $ingredient = $row['ingredient'];
    $cost = (float)$row['total_cost'];

    $label_set[$date] = true;
    $raw_data[$ingredient][$date] = $cost;
}

$labels = array_keys($label_set);
sort($labels);

// เตรียมข้อมูลสำหรับ Chart.js
$datasets = [];

foreach ($raw_data as $ingredient => $date_costs) {
    $data = [];
    foreach ($labels as $date) {
        $data[] = $date_costs[$date] ?? 0;
    }

    $datasets[] = [
        'label' => $ingredient,
        'data' => $data
    ];
}

// ส่งกลับ
echo json_encode([
    'labels' => $labels,
    'datasets' => $datasets
]);
