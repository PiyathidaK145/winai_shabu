<?php
include dirname(__FILE__) . '/../../../config/connect_db.php';

$start_date = $_POST['start_date'] ?? '2000-01-01';
$end_date = $_POST['end_date'] ?? date('Y-m-d');
$package = $_POST['package'] ?? '';
$promotion = $_POST['promotion'] ?? '';
$gender = $_POST['gender'] ?? '';
$religion = $_POST['religion'] ?? '';
$table = $_POST['table_id'] ?? '';

$conditions = [
    "pv.approve = 'completed'",
    "DATE(g.created_at) BETWEEN '$start_date' AND '$end_date'"
];

if ($package !== '') $conditions[] = "g.package_id = '$package'";
if ($promotion !== '') $conditions[] = "g.promotion_id = '$promotion'";
if ($gender !== '') $conditions[] = "(c1.gender = '$gender' OR c2.gender = '$gender')";
if ($religion !== '') $conditions[] = "(c1.religion = '$religion' OR c2.religion = '$religion')";
if ($table !== '') $conditions[] = "(ta1.table_id = " . intval($table) . " OR ta2.table_id = " . intval($table) . ")";

$where = implode(" AND ", $conditions);

$sql = "
SELECT 
    COUNT(g.getting_table_id) AS total_customers,
    COUNT(g.walkin_id) AS total_walkin,
    COUNT(g.reservation_id) AS total_reservation,
    SUM(p.total_payment) AS total_income,
    AVG(
      CASE 
        WHEN g.created_at <= p.payment_timestamp 
        THEN TIMESTAMPDIFF(MINUTE, g.created_at, p.payment_timestamp)
        ELSE TIMESTAMPDIFF(MINUTE, p.payment_timestamp, g.created_at)
      END
    ) AS avg_usage_time
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

if (!$result) {
    http_response_code(500);
    echo json_encode(['error' => 'Query failed: ' . mysqli_error($conn)]);
    exit;
}

$data = mysqli_fetch_assoc($result);

$minutes = round($data['avg_usage_time']);
$hours = floor($minutes / 60);
$mins = $minutes % 60;
$formatted_time = sprintf("%02d:%02d", $hours, $mins); // hh:mm

echo json_encode([
    'total_customers' => $data['total_customers'],
    'walkin_customers' => $data['total_walkin'],
    'reservation_customers' => $data['total_reservation'],
    'avg_time' => $formatted_time,
    'total_income' => number_format($data['total_income'], 2)
]);
