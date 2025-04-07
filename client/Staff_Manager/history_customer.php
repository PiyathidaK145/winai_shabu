<?php
date_default_timezone_set("Asia/Bangkok");
include dirname(__FILE__) . '/include/header.php';
include dirname(__FILE__) . '/../../config/connect_db.php';



$where = ["pv.approve = 'completed'"]; // เงื่อนไขเริ่มต้น

function addInCondition(&$where, $field, $paramName)
{
    global $conn;
    if (!empty($_GET[$paramName])) {
        $values = array_map(function ($val) use ($conn) {
            return "'" . mysqli_real_escape_string($conn, $val) . "'";
        }, $_GET[$paramName]);
        $where[] = "$field IN (" . implode(",", $values) . ")";
    }
}

addInCondition($where, 'c.gender', 'gender');
addInCondition($where, 'c.religion', 'religion');
addInCondition($where, 't.table_id', 'table_id');
addInCondition($where, 'p.package_name', 'package_name');
addInCondition($where, 'promo.promotions_name', 'promotions_name');
addInCondition($where, 'r.rating_selac', 'rating_avg');

if (!empty($_GET['customer_id'])) {
    $customer_id = mysqli_real_escape_string($conn, $_GET['customer_id']);
    $where[] = "c.customer_id = '$customer_id'";
}

if (!empty($_GET['first_name'])) {
    $first_name = mysqli_real_escape_string($conn, $_GET['first_name']);
    $where[] = "(
        c1.first_name LIKE '%$first_name%' OR 
        c2.first_name LIKE '%$first_name%'
    )";
}

if (!empty($_GET['last_name'])) {
    $last_name = mysqli_real_escape_string($conn, $_GET['last_name']);
    $where[] = "(
        c1.last_name LIKE '%$last_name%' OR 
        c2.last_name LIKE '%$last_name%'
    )";
}

// ประเภทการใช้บริการ
if (!empty($_GET['service_type'])) {
    if ($_GET['service_type'] === 'walkin') {
        $where[] = "g.reservation_id IS NULL";
    } elseif ($_GET['service_type'] === 'reservation') {
        $where[] = "g.walkin_id IS NULL";
    }
}

// วิธีการชำระเงิน
addInCondition($where, 'pay.payment_method', 'payment_method');

// ระยะเวลาการใช้บริการ
if (!empty($_GET['duration_range']) && is_string($_GET['duration_range'])) {
    $range = explode('-', $_GET['duration_range']);
    if (count($range) === 2) {
        $min = (int)$range[0];
        $max = (int)$range[1];
        $where[] = "(TIMESTAMPDIFF(MINUTE, g.created_at, pay.payment_timestamp) BETWEEN $min AND $max)";
    }
}

$sql = "SELECT
    r.comment_text,
    r.rating_selac,

    -- แท็กและเรตติ้ง
    tf.tag_name AS food_tag, tf.rating AS food_rating,
    tc.tag_name AS clean_tag, tc.rating AS clean_rating,
    toth.tag_name AS other_tag, toth.rating AS other_rating,
    tpr.tag_name AS price_tag, tpr.rating AS price_rating,
    ts.tag_name AS service_tag, ts.rating AS service_rating,

    -- แพ็คเกจ โปรโมชัน
    p.package_name,
    promo.promotions_name,

    -- ลูกค้า
    c.customer_id,
    c.gender,
    c.religion,

    IFNULL(c1.first_name, c2.first_name) AS first_name,
    IFNULL(c1.last_name, c2.last_name) AS last_name,

    -- โต๊ะและเวลา
    t.table_id,
    tr.time,

    -- วันที่จ่ายเงิน
    pv.created_at,

    g.walkin_id,
g.reservation_id,
g.created_at,
pay.payment_timestamp,
pay.payment_method,
pay.total_payment

FROM review r

-- TAG join
LEFT JOIN tag_food tf ON r.tag_food_id = tf.tag_food_id
LEFT JOIN tag_clean tc ON r.tag_clean_id = tc.tag_clean_id
LEFT JOIN tag_other toth ON r.tag_other_id = toth.tag_other_id
LEFT JOIN tag_price tpr ON r.tag_price_id = tpr.tag_price_id
LEFT JOIN tag_service ts ON r.tag_service_id = ts.tag_service_id

-- Receipt & Payment
LEFT JOIN receipt rec ON r.receipt_id = rec.receipt_id
LEFT JOIN payment_verificatio pv ON rec.payment_verification_id = pv.payment_verification_id
LEFT JOIN payment pay ON pv.payment_id = pay.payment_id

-- Getting Table
LEFT JOIN getting_table g ON pay.getting_table_id = g.getting_table_id
LEFT JOIN package p ON g.package_id = p.package_id
LEFT JOIN promotion promo ON g.promotion_id = promo.promotion_id

-- Walk-in
LEFT JOIN walkin w ON g.walkin_id = w.walkin_id
LEFT JOIN customer c1 ON w.customer_id = c1.customer_id
LEFT JOIN table_availability a1 ON w.availability_id = a1.availability_id
LEFT JOIN `table` t1 ON a1.table_id = t1.table_id
LEFT JOIN time_reserversion tr1 ON a1.time_id = tr1.time_id

-- Reservation
LEFT JOIN reservation rsv ON g.reservation_id = rsv.reservation_id
LEFT JOIN customer c2 ON rsv.customer_id = c2.customer_id
LEFT JOIN table_availability a2 ON rsv.availability_id = a2.availability_id
LEFT JOIN `table` t2 ON a2.table_id = t2.table_id
LEFT JOIN time_reserversion tr2 ON a2.time_id = tr2.time_id

-- เลือกลูกค้าที่มากับ walkin หรือ reservation
LEFT JOIN customer c ON (c.customer_id = c1.customer_id OR c.customer_id = c2.customer_id)
LEFT JOIN `table` t ON (t.table_id = t1.table_id OR t.table_id = t2.table_id)
LEFT JOIN time_reserversion tr ON (tr.time_id = tr1.time_id OR tr.time_id = tr2.time_id)

WHERE " . implode(" AND ", $where);

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>ประวัติการใช้บริการของลูกค้า</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="row">
        <main class="main-wrapper col-md-9 ms-sm-auto py-4 col-lg-9 px-md-4 border-start">
            <div class="container mt-4">
                <h3 class="mb-4"><strong>ประวัติการใช้บริการของลูกค้า</strong></h3>
                <form method="GET" action="history_customer.php" class="mb-3">
                    <div class="row g-2 align-items-end">

                        <div class="col-md-2">
                            <label class="form-label">รหัสลูกค้า</label>
                            <input type="text" name="customer_id" class="form-control" value="<?= $_GET['customer_id'] ?? '' ?>">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">ชื่อ</label>
                            <input type="text" name="first_name" class="form-control" value="<?= $_GET['first_name'] ?? '' ?>">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label">นามสกุล</label>
                            <input type="text" name="last_name" class="form-control" value="<?= $_GET['last_name'] ?? '' ?>">
                        </div>

                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100 mt-4">
                                <i class="fa fa-search me-1"></i> ค้นหา
                            </button>
                        </div>

                        <div class="col-md-2">
                            <button type="button" class="btn btn-secondary w-100 mt-4" data-bs-toggle="modal" data-bs-target="#filterModal">
                                <i class="fa-solid fa-filter me-1"></i> Filter
                            </button>
                        </div>

                    </div>
                </form>

                <?php include 'filter_model_history.php'; ?>

                <div class="table-responsive">
                    <table id="tableUse" class="table_use table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ลำดับ</th>
                                <th>ชื่อ</th>
                                <th>สกุล</th>
                                <th>การใช้บริการ</th>
                                <th>แพ็คเกจ</th>
                                <th>โปรโมชัน</th>
                                <th>โต๊ะ</th>
                                <th>เวลาเริ่มต้น</th>
                                <th>เวลาสิ้นสุด</th>
                                <th>ระยะเวลาที่ใช้บริการ</th>
                                <th>วิธีการชำระเงิน</th>
                                <th>ยอดชำระ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            while ($row = mysqli_fetch_assoc($result)) {
                                // การใช้บริการ
                                if (!empty($row['walkin_id'])) {
                                    $service_type = 'Walk-in';
                                } elseif (!empty($row['reservation_id'])) {
                                    $service_type = 'Reservation';
                                } else {
                                    $service_type = 'ไม่ทราบประเภท';
                                }

                                // ชื่อโต๊ะ
                                $table_id = $row['table_id'] ?? '-';

                                // เวลาการใช้บริการ
                                $start_time = $row['created_at'] ?? '-';
                                $end_time = $row['payment_timestamp'] ?? '-';

                                // คำนวณระยะเวลาใช้บริการ
                                $duration = '-';
                                if ($start_time !== '-' && $end_time !== '-') {
                                    $start = new DateTime($start_time);
                                    $end = new DateTime($end_time);
                                    $interval = $start->diff($end);

                                    // แสดงวินาทีด้วยหากเวลาห่างน้อยกว่า 1 นาที
                                    if ($interval->h == 0 && $interval->i == 0) {
                                        $duration = $interval->format('%s วินาที');
                                    } else {
                                        $duration = $interval->format('%h ชั่วโมง %i นาที %s วินาที');
                                    }
                                }

                                // วิธีชำระเงิน
                                $payment_method = $row['payment_method'] ?? '-';

                                // ยอดชำระ
                                $total_payment = $row['total_payment'] ?? '0';

                                echo "<tr>
        <td>{$i}</td>
        <td>{$row['first_name']}</td>
        <td>{$row['last_name']}</td>
        <td>{$service_type}</td>
        <td>{$row['package_name']}</td>
        <td>{$row['promotions_name']}</td>
        <td>{$table_id}</td>
        <td>{$start_time}</td>
        <td>{$end_time}</td>
        <td>{$duration}</td>
        <td>{$payment_method}</td>
        <td>{$total_payment}</td>
    </tr>";
                                $i++;
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <?php include dirname(__FILE__) . '/include/footer.php'; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>