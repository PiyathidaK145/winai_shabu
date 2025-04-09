<?php
include dirname(__FILE__) . '/../../../config/connect_db.php';

$start_date = $_POST['start_date'] ?? '2000-01-01';
$end_date = $_POST['end_date'] ?? date('Y-m-d');
$package = $_POST['package'] ?? '';
$gender = $_POST['gender'] ?? '';
$religion = $_POST['religion'] ?? '';
$service_type = $_POST['service_type'] ?? '';
$table = $_POST['table_id'] ?? '';

// ✅ ใช้ array แบบถูกต้อง
$conditions = ["DATE(g.created_at) BETWEEN '$start_date' AND '$end_date'", "pv.approve = 'completed'"];

if ($package !== '') $conditions[] = "g.package_id = '$package'";
if ($gender !== '') $conditions[] = "(c_walkin.gender = '$gender' OR c_re.gender = '$gender')";
if ($religion !== '') $conditions[] = "(c_walkin.religion = '$religion' OR c_re.religion = '$religion')";
if ($service_type === 'walkin') $conditions[] = "g.walkin_id IS NOT NULL";
if ($service_type === 'reservation') $conditions[] = "g.reservation_id IS NOT NULL";
if ($table !== '') $conditions[] = "(a_walkin.table_id = '$table' OR a_re.table_id = '$table')";

// ✅ สร้าง WHERE clause
$where = implode(" AND ", $conditions);

// ✅ SQL แบบสมบูรณ์
$sql = "
    SELECT promo.promotions_name AS promotion_name, COUNT(*) AS count
    FROM payment_verificatio pv
    JOIN payment p ON pv.payment_id = p.payment_id
    JOIN getting_table g ON p.getting_table_id = g.getting_table_id
    LEFT JOIN walkin w ON g.walkin_id = w.walkin_id
    LEFT JOIN customer c_walkin ON w.customer_id = c_walkin.customer_id
    LEFT JOIN table_availability a_walkin ON w.availability_id = a_walkin.availability_id
    LEFT JOIN reservation r ON g.reservation_id = r.reservation_id
    LEFT JOIN customer c_re ON r.customer_id = c_re.customer_id
    LEFT JOIN table_availability a_re ON r.availability_id = a_re.availability_id
    LEFT JOIN promotion promo ON g.promotion_id = promo.promotion_id
    WHERE $where
    GROUP BY g.promotion_id
";

$result = mysqli_query($conn, $sql);

$labels = [];
$values = [];
$colors = [];

$colorPalette = ['#03a9f4', '#e91e63', '#8bc34a', '#ff5722', '#00bcd4', '#673ab7', '#ffc107'];

$i = 0;
while ($row = mysqli_fetch_assoc($result)) {
    $labels[] = $row['promotion_name'] ?: 'ไม่ระบุโปรโมชั่น';
    $values[] = (int)$row['count'];
    $colors[] = $colorPalette[$i % count($colorPalette)];
    $i++;
}

echo json_encode([
    'labels' => $labels,
    'values' => $values,
    'colors' => $colors
]);
