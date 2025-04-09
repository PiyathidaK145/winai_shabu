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

$sql = "
    SELECT 
        DATE(ir.create_at) AS date,
        r.item_name AS ingredient_name,
        COUNT(*) AS import_count
    FROM import_raw_material ir
    JOIN menu m ON ir.menu_id = m.menu_id
    JOIN raw_material r ON m.raw_material_id = r.raw_material_id
    $where_sql
    GROUP BY r.item_name, DATE(ir.create_at)
    ORDER BY date ASC
";

$result = mysqli_query($conn, $sql);

$labelSet = [];
$dataMap = [];

while ($row = mysqli_fetch_assoc($result)) {
    $date = $row['date'];
    $ingredient = $row['ingredient_name'];
    $count = (int) $row['import_count'];

    $labelSet[$date] = true;
    $dataMap[$ingredient][$date] = $count;
}

$labels = array_keys($labelSet);
sort($labels);

$datasets = [];
foreach ($dataMap as $ingredient => $dateCounts) {
    $data = [];
    foreach ($labels as $label) {
        $data[] = $dateCounts[$label] ?? 0;
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
