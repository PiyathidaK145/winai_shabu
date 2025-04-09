<?php
include '../../config/connect_db.php';
header('Content-Type: application/json');

// รับค่าจาก POST
$start_date = $_POST['start_date'] ?? null;
$end_date = $_POST['end_date'] ?? null;
$menu_ids = $_POST['menu_ids'] ?? []; // array

$menu_filter_sql = '';

// เงื่อนไข WHERE
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

// 1. จำนวนวัตถุดิบทั้งหมด = SUM(r.capacity * m.quantity_of_sale)
$sql1 = "
    SELECT SUM(r.capacity * m.quantity_of_sale) AS total_ingredients, m.unit
    FROM menu AS m
    JOIN raw_material AS r ON m.raw_material_id = r.raw_material_id
";
$result1 = mysqli_query($conn, $sql1);
$row1 = mysqli_fetch_assoc($result1);
$total_ingredients = number_format($row1['total_ingredients']) . ' ' . $row1['unit'];

// 2. จำนวนครั้งที่นำเข้า
$sql2 = "SELECT COUNT(*) AS total_imports FROM import_raw_material AS ir $where_sql";
$result2 = mysqli_query($conn, $sql2);
$row2 = mysqli_fetch_assoc($result2);
$import_times = (int) $row2['total_imports'];

// 3. ปริมาณการนำเข้า = SUM(ir.quantity * r.quanity)
$sql3 = "
    SELECT SUM(ir.quantity * r.quanity) AS total_volume, m.unit
    FROM import_raw_material AS ir
    JOIN menu AS m ON ir.menu_id = m.menu_id
    JOIN raw_material AS r ON m.raw_material_id = r.raw_material_id
    $where_sql
";
$result3 = mysqli_query($conn, $sql3);
$row3 = mysqli_fetch_assoc($result3);
$total_volume = number_format($row3['total_volume']) . ' ' . $row3['unit'];

// 4. จำนวนค่าใช้จ่าย
$sql4 = "SELECT SUM(ir.cost) AS total_cost FROM import_raw_material AS ir $where_sql";
$result4 = mysqli_query($conn, $sql4);
$row4 = mysqli_fetch_assoc($result4);
$total_cost = number_format($row4['total_cost']) . ' บาท';

$start = new DateTime($start_date);
$end = new DateTime($end_date);
$interval = $start->diff($end)->days;

if ($interval <= 7) {
    $group_by = "DATE(ir.create_at)";
    $label_format = "%e %b"; // ex: 1 เม.ย.
} elseif ($interval <= 30) {
    $group_by = "YEARWEEK(ir.create_at, 1)";
    $label_format = "สัปดาห์ที่ %v";
} else {
    $group_by = "DATE_FORMAT(ir.create_at, '%Y-%m')"; // ex: 2025-04
    $label_format = "%b %Y";
}
$sql_chart = "
    SELECT 
    DATE(ir.create_at) AS date,
    r.item_name AS ingredient_name,
    SUM(m.quantity_of_sale * r.capacity) AS total
FROM import_raw_material ir
JOIN menu m ON ir.menu_id = m.menu_id
JOIN raw_material r ON m.raw_material_id = r.raw_material_id
WHERE ir.create_at BETWEEN '$start_date 00:00:00' AND '$end_date 23:59:59'
" . (!empty($menu_ids) ? "AND ir.menu_id IN ($menu_ids_safe)" : "") . "
GROUP BY ingredient_name, date
ORDER BY date ASC;

";


$result_chart = mysqli_query($conn, $sql_chart);

$chartData = [];
$labelSet = [];

while ($row = mysqli_fetch_assoc($result_chart)) {
    $date = $row['date'];
    $ingredient = $row['ingredient_name'];
    $total = (float) $row['total'];

    $labelSet[$date] = true;
    $chartData[$ingredient][$date] = $total; 
}

$labels = array_keys($labelSet);
sort($labels);

// เตรียม datasets แยกตามวัตถุดิบ
$datasets = [];
foreach ($chartData as $ingredient => $dataPerDate) {
    $data = [];
    foreach ($labels as $label) {
        $data[] = $dataPerDate[$label] ?? 0;
    }
    $datasets[] = [
        'label' => $ingredient,
        'data' => $data,
    ];
}



// ✅ ส่งกลับ
echo json_encode([
    'total_ingredients' => $total_ingredients,
    'import_times' => $import_times,
    'total_volume' => $total_volume,
    'total_cost' => $total_cost,
    'chart_ingredients_count' => [
        'labels' => $labels,
        'datasets' => $datasets
    ]
]);
