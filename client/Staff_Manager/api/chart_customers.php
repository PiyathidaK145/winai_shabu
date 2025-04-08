<?php
include dirname(__FILE__) . '/../../../config/connect_db.php';

// รับค่าตัวกรอง
$start = $_POST['start_date'] ?? date('Y-m-01');
$end = $_POST['end_date'] ?? date('Y-m-d');
$package = $_POST['package'] ?? '';
$promotion = $_POST['promotion'] ?? '';
$gender = $_POST['gender'] ?? '';
$religion = $_POST['religion'] ?? '';
$service_type = $_POST['service_type'] ?? '';
$table = $_POST['table_id'] ?? '';

// เงื่อนไข
$conditions = ["DATE(g.created_at) BETWEEN '$start' AND '$end'", "pv.approve = 'completed'"];
if ($package !== '') $conditions[] = "g.package_id = '$package'";
if ($promotion !== '') $conditions[] = "g.promotion_id = '$promotion'";
if ($gender !== '') $conditions[] = "(c1.gender = '$gender' OR c2.gender = '$gender')";
if ($religion !== '') $conditions[] = "(c1.religion = '$religion' OR c2.religion = '$religion')";
if ($service_type === 'walkin') $conditions[] = "g.walkin_id IS NOT NULL";
if ($service_type === 'reservation') $conditions[] = "g.reservation_id IS NOT NULL";
if ($table !== '') $conditions[] = "(ta1.table_id = '$table' OR ta2.table_id = '$table')";

$where = 'WHERE ' . implode(' AND ', $conditions);

// SQL Query
$sql = "
  SELECT DATE(g.created_at) AS date, COUNT(*) AS total
  FROM payment_verificatio pv
  JOIN payment p ON pv.payment_id = p.payment_id
  JOIN getting_table g ON p.getting_table_id = g.getting_table_id

  LEFT JOIN walkin w ON g.walkin_id = w.walkin_id
  LEFT JOIN customer c1 ON w.customer_id = c1.customer_id
  LEFT JOIN table_availability ta1 ON w.availability_id = ta1.availability_id

  LEFT JOIN reservation r ON g.reservation_id = r.reservation_id
  LEFT JOIN customer c2 ON r.customer_id = c2.customer_id
  LEFT JOIN table_availability ta2 ON r.availability_id = ta2.availability_id

  $where
  GROUP BY DATE(g.created_at)
  ORDER BY DATE(g.created_at) ASC
";

// รันและคืนค่าข้อมูล
$result = mysqli_query($conn, $sql);

$labels = [];
$values = [];
while ($row = mysqli_fetch_assoc($result)) {
  $labels[] = $row['date'];
  $values[] = (int) $row['total'];
}

echo json_encode([
  'label' => 'จำนวนลูกค้า',
  'labels' => $labels,
  'values' => $values
]);
