<?php
include dirname(__FILE__) . '/../../../config/connect_db.php';

$start_date = $_POST['start_date'] ?? '2000-01-01';
$end_date = $_POST['end_date'] ?? date('Y-m-d');
$package = $_POST['package'] ?? '';
$promotion = $_POST['promotion'] ?? '';
$gender = $_POST['gender'] ?? '';
$religion = $_POST['religion'] ?? '';
$table = $_POST['table_id'] ?? '';


$conditions = ["pv.approve = 'completed'", "DATE(g.created_at) BETWEEN '$start_date' AND '$end_date'"];

if ($package !== '') $conditions[] = "g.package_id = '$package'";
if ($promotion !== '') $conditions[] = "g.promotion_id = '$promotion'";
if ($gender !== '') $conditions[] = "(c1.gender = '$gender' OR c2.gender = '$gender')";
if ($religion !== '') $conditions[] = "(c1.religion = '$religion' OR c2.religion = '$religion')";
if ($table !== '') $conditions[] = "(ta1.table_id = " . intval($table) . " OR ta2.table_id = " . intval($table) . ")";
$where = implode(" AND ", $conditions);

$sql = "
SELECT 
    SUM(CASE WHEN g.walkin_id IS NOT NULL THEN 1 ELSE 0 END) AS walkin,
    SUM(CASE WHEN g.reservation_id IS NOT NULL THEN 1 ELSE 0 END) AS reservation
FROM payment_verificatio pv
JOIN payment p ON pv.payment_id = p.payment_id
JOIN getting_table g ON p.getting_table_id = g.getting_table_id

LEFT JOIN walkin w ON g.walkin_id = w.walkin_id
LEFT JOIN customer c1 ON w.customer_id = c1.customer_id
LEFT JOIN table_availability ta1 ON w.availability_id = ta1.availability_id
LEFT JOIN `table` t1 ON ta1.table_id = t1.table_id

LEFT JOIN reservation r ON g.reservation_id = r.reservation_id
LEFT JOIN customer c2 ON r.customer_id = c2.customer_id
LEFT JOIN table_availability ta2 ON r.availability_id = ta2.availability_id
LEFT JOIN `table` t2 ON ta2.table_id = t2.table_id

WHERE $where
";

$result = mysqli_query($conn, $sql);
$data = mysqli_fetch_assoc($result);

echo json_encode([
    'labels' => ['Walk-in', 'Reservation'],
    'values' => [
        intval($data['walkin']),
        intval($data['reservation'])
    ]
]);
