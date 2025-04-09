<?php
include dirname(__FILE__) . '/../../../config/connect_db.php';

$start = $_POST['start_date'] ?? '2000-01-01';
$end = $_POST['end_date'] ?? date('Y-m-d');
$package = $_POST['package'] ?? '';
$promotion = $_POST['promotion'] ?? '';
$gender = $_POST['gender'] ?? '';
$religion = $_POST['religion'] ?? '';
$service_type = $_POST['service_type'] ?? '';
$table = $_POST['table_id'] ?? '';

$conditions = ["DATE(g.created_at) BETWEEN '$start' AND '$end'", "pv.approve = 'completed'"];

if ($package !== '') $conditions[] = "g.package_id = '$package'";
if ($promotion !== '') $conditions[] = "g.promotion_id = '$promotion'";
if ($gender !== '') $conditions[] = "(c1.gender = '$gender' OR c2.gender = '$gender')";
if ($religion !== '') $conditions[] = "(c1.religion = '$religion' OR c2.religion = '$religion')";
if ($service_type === 'walkin') $conditions[] = "g.walkin_id IS NOT NULL";
if ($service_type === 'reservation') $conditions[] = "g.reservation_id IS NOT NULL";
if ($table !== '') $conditions[] = "(ta1.table_id = '$table' OR ta2.table_id = '$table')";

$where = 'WHERE ' . implode(' AND ', $conditions);

// 🔸 คะแนนเฉลี่ย
$sql_avg = "
SELECT AVG(review.rating_selac) as avg_score
FROM review
JOIN receipt r ON review.receipt_id = r.receipt_id
JOIN payment_verificatio pv ON r.payment_verification_id = pv.payment_verification_id
JOIN payment p ON pv.payment_id = p.payment_id
JOIN getting_table g ON p.getting_table_id = g.getting_table_id
LEFT JOIN walkin w ON g.walkin_id = w.walkin_id
LEFT JOIN customer c1 ON w.customer_id = c1.customer_id
LEFT JOIN table_availability ta1 ON w.availability_id = ta1.availability_id
LEFT JOIN reservation rsv ON g.reservation_id = rsv.reservation_id
LEFT JOIN customer c2 ON rsv.customer_id = c2.customer_id
LEFT JOIN table_availability ta2 ON rsv.availability_id = ta2.availability_id
$where
";
$avg_result = mysqli_query($conn, $sql_avg);
$avg_score = round(mysqli_fetch_assoc($avg_result)['avg_score'], 2);

// 🔸 ความถี่ของคะแนน 1-5
$sql_dist = "
SELECT review.rating_selac, COUNT(*) as count
FROM review
JOIN receipt r ON review.receipt_id = r.receipt_id
JOIN payment_verificatio pv ON r.payment_verification_id = pv.payment_verification_id
JOIN payment p ON pv.payment_id = p.payment_id
JOIN getting_table g ON p.getting_table_id = g.getting_table_id
LEFT JOIN walkin w ON g.walkin_id = w.walkin_id
LEFT JOIN customer c1 ON w.customer_id = c1.customer_id
LEFT JOIN table_availability ta1 ON w.availability_id = ta1.availability_id
LEFT JOIN reservation rsv ON g.reservation_id = rsv.reservation_id
LEFT JOIN customer c2 ON rsv.customer_id = c2.customer_id
LEFT JOIN table_availability ta2 ON rsv.availability_id = ta2.availability_id
$where
GROUP BY review.rating_selac
ORDER BY review.rating_selac ASC
";
$dist_result = mysqli_query($conn, $sql_dist);
$labels = []; $values = [];
while ($row = mysqli_fetch_assoc($dist_result)) {
  $labels[] = "ระดับ " . $row['rating_selac'];
  $values[] = $row['count'];
}

// 🔸 ฟังก์ชันหาชื่อ tag ที่ได้บ่อยสุด
function getTopTag($conn, $tag_id_field, $tag_table, $tag_name_field, $where) {
  $sql = "
    SELECT tg.$tag_name_field AS name, COUNT(*) as total
    FROM review
    JOIN receipt r ON review.receipt_id = r.receipt_id
    JOIN payment_verificatio pv ON r.payment_verification_id = pv.payment_verification_id
    JOIN payment p ON pv.payment_id = p.payment_id
    JOIN getting_table g ON p.getting_table_id = g.getting_table_id

    LEFT JOIN walkin w ON g.walkin_id = w.walkin_id
    LEFT JOIN customer c1 ON w.customer_id = c1.customer_id
    LEFT JOIN table_availability ta1 ON w.availability_id = ta1.availability_id

    LEFT JOIN reservation rsv ON g.reservation_id = rsv.reservation_id
    LEFT JOIN customer c2 ON rsv.customer_id = c2.customer_id
    LEFT JOIN table_availability ta2 ON rsv.availability_id = ta2.availability_id

    JOIN $tag_table tg ON review.$tag_id_field = tg.{$tag_table}_id
    $where
    GROUP BY tg.$tag_name_field
    ORDER BY total DESC
    LIMIT 1
  ";

  $res = mysqli_query($conn, $sql);
  if ($row = mysqli_fetch_assoc($res)) return $row['name'];
  return '-';
}


// 🔸 ดึง tag ที่ถูกเลือกบ่อยที่สุด
$tags = [
  'service' => getTopTag($conn, 'tag_service_id', 'tag_service', 'tag_name', $where),
  'cleanliness' => getTopTag($conn, 'tag_clean_id', 'tag_clean', 'tag_name', $where),
  'food' => getTopTag($conn, 'tag_food_id', 'tag_food', 'tag_name', $where),
  'price' => getTopTag($conn, 'tag_price_id', 'tag_price', 'tag_name', $where),
  'other' => getTopTag($conn, 'tag_other_id', 'tag_other', 'tag_name', $where),
];


// 🔸 ส่งออก
echo json_encode([
  'avg_score' => $avg_score,
  'labels' => $labels,
  'values' => $values,
  'tags' => $tags
]);
