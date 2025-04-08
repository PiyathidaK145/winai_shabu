<?php
include dirname(__FILE__) . '/../../../config/connect_db.php';

$start = $_POST['start_date'] ?? date('Y-m-01');
$end = $_POST['end_date'] ?? date('Y-m-d');
$package = $_POST['package'] ?? '';
$promotion = $_POST['promotion'] ?? '';
$gender = $_POST['gender'] ?? '';
$religion = $_POST['religion'] ?? '';
$service_type = $_POST['service_type'] ?? '';
$table = $_POST['table_id'] ?? '';

// 🔸 เงื่อนไขการกรอง
$conditions = ["DATE(g.created_at) BETWEEN '$start' AND '$end'", "pv.approve = 'completed'"];

if ($package !== '') $conditions[] = "g.package_id = '$package'";
if ($promotion !== '') $conditions[] = "g.promotion_id = '$promotion'";
if ($gender !== '') $conditions[] = "(c_walkin.gender = '$gender' OR c_re.gender = '$gender')";
if ($religion !== '') $conditions[] = "(c_walkin.religion = '$religion' OR c_re.religion = '$religion')";
if ($service_type === 'walkin') $conditions[] = "g.walkin_id IS NOT NULL";
if ($service_type === 'reservation') $conditions[] = "g.reservation_id IS NOT NULL";
if ($table !== '') $conditions[] = "(a_walkin.table_id = '$table' OR a_re.table_id = '$table')";

$where = 'WHERE ' . implode(" AND ", $conditions);

// 🔸 SQL Statement
$sql = "
    SELECT DATE(g.created_at) AS date, AVG(rvw.rating_selac) AS total
    FROM review rvw
    JOIN receipt rc ON rvw.receipt_id = rc.receipt_id
    JOIN payment_verificatio pv ON rc.payment_verification_id = pv.payment_verification_id
    JOIN payment p ON pv.payment_id = p.payment_id
    JOIN getting_table g ON p.getting_table_id = g.getting_table_id

    -- Walk-in
    LEFT JOIN walkin w ON g.walkin_id = w.walkin_id
    LEFT JOIN customer c_walkin ON w.customer_id = c_walkin.customer_id
    LEFT JOIN table_availability a_walkin ON w.availability_id = a_walkin.availability_id

    -- Reservation
    LEFT JOIN reservation r ON g.reservation_id = r.reservation_id
    LEFT JOIN customer c_re ON r.customer_id = c_re.customer_id
    LEFT JOIN table_availability a_re ON r.availability_id = a_re.availability_id

    -- แพ็คเกจ/โปรโมชั่น
    LEFT JOIN package pk ON g.package_id = pk.package_id
    LEFT JOIN promotion promo ON g.promotion_id = promo.promotion_id
    LEFT JOIN promotion_item pi ON promo.promotion_id = pi.promotion_id

    $where
    GROUP BY DATE(g.created_at)
    ORDER BY DATE(g.created_at) ASC
";

// 🔸 ดึงข้อมูลจากฐานข้อมูล
$result = mysqli_query($conn, $sql);

$labels = [];
$values = [];

while ($row = mysqli_fetch_assoc($result)) {
    $labels[] = $row['date'];
    $values[] = round(floatval($row['total']), 2); // ปัดคะแนนให้ดูสวยงาม
}

echo json_encode([
    'label' => 'คะแนนความพึงพอใจ',
    'labels' => $labels,
    'values' => $values
]);
