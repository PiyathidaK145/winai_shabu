<?php
include dirname(__FILE__) . '/../../../config/connect_db.php';

$start_date = $_POST['start_date'] ?? '2000-01-01';
$end_date = $_POST['end_date'] ?? date('Y-m-d');
$service_type = $_POST['service_type'] ?? '';
$gender = $_POST['gender'] ?? '';
$religion = $_POST['religion'] ?? '';
$table = $_POST['table_id'] ?? '';

// 🔸 เงื่อนไข WHERE
$conditions = ["DATE(g.created_at) BETWEEN '$start_date' AND '$end_date'", "pv.approve = 'completed'"];
if ($service_type === 'walkin') $conditions[] = "g.walkin_id IS NOT NULL";
if ($service_type === 'reservation') $conditions[] = "g.reservation_id IS NOT NULL";
if ($gender !== '') $conditions[] = "(c_walkin.gender = '$gender' OR c_re.gender = '$gender')";
if ($religion !== '') $conditions[] = "(c_walkin.religion = '$religion' OR c_re.religion = '$religion')";
if ($table !== '') $conditions[] = "(a_walkin.table_id = '$table' OR a_re.table_id = '$table')";

$where = 'WHERE ' . implode(' AND ', $conditions);

// 🔸 JOIN รวม
$joins = "
    LEFT JOIN walkin w ON g.walkin_id = w.walkin_id
    LEFT JOIN customer c_walkin ON w.customer_id = c_walkin.customer_id
    LEFT JOIN table_availability a_walkin ON w.availability_id = a_walkin.availability_id

    LEFT JOIN reservation r ON g.reservation_id = r.reservation_id
    LEFT JOIN customer c_re ON r.customer_id = c_re.customer_id
    LEFT JOIN table_availability a_re ON r.availability_id = a_re.availability_id
";

// 🔸 ฟังก์ชันหาแพ็คเกจหรือโปรโมชันที่ใช้บ่อย
function getMostUsed($conn, $table_name, $join_field, $label_field, $joins, $where) {
    $sql = "
        SELECT $table_name.$label_field AS label, COUNT(*) AS count
        FROM payment_verificatio pv
        JOIN payment p ON pv.payment_id = p.payment_id
        JOIN getting_table g ON p.getting_table_id = g.getting_table_id
        $joins
        LEFT JOIN $table_name ON $table_name.$join_field = g.$join_field
        $where
        GROUP BY $table_name.$label_field
        ORDER BY count DESC
        LIMIT 1
    ";
    $result = mysqli_query($conn, $sql);
    if ($row = mysqli_fetch_assoc($result)) {
        return $row['label'];
    }
    return null;
}

// 🔸 ฟังก์ชันหาโต๊ะที่ถูกใช้งานบ่อยที่สุด
function getMostUsedTable($conn, $joins, $where) {
    $sql = "
        SELECT COALESCE(a_walkin.table_id, a_re.table_id) AS table_id, COUNT(*) AS count
        FROM payment_verificatio pv
        JOIN payment p ON pv.payment_id = p.payment_id
        JOIN getting_table g ON p.getting_table_id = g.getting_table_id
        $joins
        $where
        GROUP BY table_id
        ORDER BY count DESC
        LIMIT 1
    ";
    $result = mysqli_query($conn, $sql);
    if ($row = mysqli_fetch_assoc($result)) {
        return $row['table_id'];
    }
    return null;
}

// 🔸 ดึงข้อมูลยอดนิยม
$popular_package = getMostUsed($conn, 'package', 'package_id', 'package_name', $joins, $where);
$popular_promotion = getMostUsed($conn, 'promotion', 'promotion_id', 'promotions_name', $joins, $where);
$popular_table = getMostUsedTable($conn, $joins, $where);

// 🔸 ส่งออก JSON
echo json_encode([
    'popular_package' => $popular_package ?? 'ไม่มีข้อมูล',
    'popular_promotion' => $popular_promotion ?? 'ไม่มีข้อมูล',
    'popular_table' => $popular_table ?? 'ไม่มีข้อมูล'
]);
