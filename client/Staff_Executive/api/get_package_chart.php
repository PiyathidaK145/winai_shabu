<?php
include dirname(__FILE__) . '/../../../config/connect_db.php';

$start_date = $_POST['start_date'] ?? '2000-01-01';
$end_date = $_POST['end_date'] ?? date('Y-m-d');
$promotion = $_POST['promotion'] ?? '';
$gender = $_POST['gender'] ?? '';
$religion = $_POST['religion'] ?? '';
$service_type = $_POST['service_type'] ?? '';
$table = $_POST['table_id'] ?? '';

$conditions = ["pv.approve = 'completed'", "DATE(g.created_at) BETWEEN '$start_date' AND '$end_date'"];

if ($promotion !== '') $conditions[] = "services.promotion_id = '$promotion'";
if ($gender !== '') $conditions[] = "(c1.gender = '$gender' OR c2.gender = '$gender')";
if ($religion !== '') $conditions[] = "(c1.religion = '$religion' OR c2.religion = '$religion')";
if ($service_type !== '') {
    if ($service_type === 'walkin') $conditions[] = "g.walkin_id IS NOT NULL";
    else if ($service_type === 'reservation') $conditions[] = "g.reservation_id IS NOT NULL";
}
if ($table !== '') $conditions[] = "(ta1.table_id = " . intval($table) . " OR ta2.table_id = " . intval(value: $table) . ")";


$where = implode(" AND ", $conditions);

$sql = "
SELECT 
    p.package_name, 
    COUNT(*) as count
FROM payment_verificatio pv
JOIN payment pay ON pv.payment_id = pay.payment_id
JOIN getting_table g ON pay.getting_table_id = g.getting_table_id
JOIN package p ON g.package_id = p.package_id

LEFT JOIN walkin w ON g.walkin_id = w.walkin_id
LEFT JOIN customer c1 ON w.customer_id = c1.customer_id
LEFT JOIN table_availability ta1 ON w.availability_id = ta1.availability_id
LEFT JOIN `table` t1 ON ta1.table_id = t1.table_id

LEFT JOIN reservation r ON g.reservation_id = r.reservation_id
LEFT JOIN customer c2 ON r.customer_id = c2.customer_id
LEFT JOIN table_availability ta2 ON r.availability_id = ta2.availability_id
LEFT JOIN `table` t2 ON ta2.table_id = t2.table_id

WHERE $where
GROUP BY g.package_id
";

$result = mysqli_query($conn, $sql);

$labels = [];
$values = [];
$colors = [];

$colorPalette = ['#f44336', '#3f51b5', '#4caf50', '#ff9800', '#9c27b0', '#2196f3', '#009688', '#795548', '#607d8b'];

$i = 0;
while ($row = mysqli_fetch_assoc($result)) {
    $labels[] = $row['package_name'];
    $values[] = $row['count'];
    $colors[] = $colorPalette[$i % count($colorPalette)];
    $i++;
}

echo json_encode([
    'labels' => $labels,
    'values' => $values,
    'colors' => $colors
]);
