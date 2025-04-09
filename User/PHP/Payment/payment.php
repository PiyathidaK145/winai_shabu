<?php
include '../../../config/connect_db.php';

header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// รับค่า getting_table_id
$gettingTableId = isset($_GET['getting_table_id']) ? $_GET['getting_table_id'] : null;
if (!$gettingTableId) {
    die("getting_table_id is required");
}

// ดึงข้อมูล reservation_id และ walkin_id จาก getting_table
$query = "
    SELECT reservation_id, walkin_id 
    FROM getting_table 
    WHERE getting_table_id = :getting_table_id
    LIMIT 1
";
$stmt = $pdo->prepare($query);
$stmt->execute(['getting_table_id' => $gettingTableId]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

// ตรวจสอบว่ามีค่าใน reservation_id หรือ walkin_id
if ($row) {
    $reservationId = $row['reservation_id'];
    $walkinId = $row['walkin_id'];

    // กำหนดการค้นหาข้อมูลตามที่มีค่าจากคอลัมใดคอลัมหนึ่ง
    if ($reservationId) {
        // หากมี reservation_id
        $query = "
            SELECT 
                gt.getting_table_id, 
                gt.promotion_id,  
                ta.table_id,
                p.package_name, 
                p.price,
                r.number_of_guest,
                (r.number_of_guest * p.price) AS total_price
            FROM getting_table gt
            JOIN package p ON gt.package_id = p.package_id
            JOIN reservation r ON gt.reservation_id = r.reservation_id
            JOIN table_availability ta ON r.availability_id = ta.availability_id
            WHERE gt.reservation_id = :reservation_id
            LIMIT 1
        ";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['reservation_id' => $reservationId]);
    } elseif ($walkinId) {
        // หากมี walkin_id
        $query = "
            SELECT 
                gt.getting_table_id, 
                gt.promotion_id,  
                ta.table_id,
                p.package_name, 
                p.price,
                w.number_of_guest, 
                (w.number_of_guest * p.price) AS total_price
            FROM getting_table gt
            JOIN package p ON gt.package_id = p.package_id
            JOIN walkin w ON gt.walkin_id = w.walkin_id
            JOIN table_availability ta ON w.availability_id = ta.availability_id
            WHERE gt.walkin_id = :walkin_id
            LIMIT 1
        ";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['walkin_id' => $walkinId]);
    }

    // ดึงข้อมูลมาเก็บในตัวแปร
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $getting_table_id = $result['getting_table_id'];
        $promotionId = $result['promotion_id'];
        $table_id = $result['table_id'];
        $package_name = $result['package_name'];
        $price = $result['price'];
        $number_of_guest = $result['number_of_guest'];
        $total_price = $result['total_price'];
    } else {
        die("ไม่พบข้อมูลการจองหรือ Walk-in");
    }
} else {
    die("ไม่พบข้อมูล getting_table_id นี้");
}

// ค่าเริ่มต้นของส่วนลด
$discount_value_display = "0"; // ใช้สำหรับแสดงผล
$discount_value_calc = 0; // ใช้คำนวณยอดสุทธิ

if ($promotionId) {
    // ดึงข้อมูลประเภทส่วนลดและค่าโปรโมชั่นจากฐานข้อมูล
    $query = "
        SELECT pi.discount_type, pi.discount_value, p.promotions_name
        FROM promotion_item pi
        JOIN promotion p ON pi.promotion_id = p.promotion_id
        WHERE pi.promotion_id = ? 
            AND pi.status = 'active' 
        LIMIT 1
    ";

    // เตรียมคำสั่ง SQL
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $promotionId);  // ใช้การ bind ข้อมูลตามตัวแปร promotionId
    $stmt->execute();
    $result = $stmt->get_result();
    $promotion = $result->fetch_assoc();  // ดึงข้อมูลผลลัพธ์จากฐานข้อมูล

    // ตรวจสอบว่าได้ข้อมูลโปรโมชันหรือไม่
    if ($promotion) {
        // ดึงข้อมูลจำนวนคนที่ต้องจ่าย
        $sql_promo_item = "
            SELECT pi.promotion_id, pi.pay_people
            JOIN promotion p ON pi.promotion_id = p.promotion_id
            WHERE pi.promotion_id = ? 
              AND pi.status = 'active'
            GROUP BY pi.promotion_id, pi.pay_people
            LIMIT 1
        ";

        // เตรียมคำสั่ง SQL สำหรับจำนวนคนที่ต้องจ่าย
        $stmt2 = $conn->prepare($sql_promo_item);
        $stmt2->bind_param("i", $promotionId);  // ใช้การ bind ข้อมูลตามตัวแปร promotionId
        $stmt2->execute();
        $result2 = $stmt2->get_result();
        $row = $result2->fetch_assoc();  // ดึงข้อมูลผลลัพธ์จากฐานข้อมูล

        if ($row) {
            $pay_people = $row['pay_people'];
        } else {
            $pay_people = 0;
        }

        // คำนวณส่วนลด
        if ($promotion['discount_type'] === 'percentage') {
            $discount_value_calc = ($total_price * $promotion['discount_value']) / 100;
            $discount_value_display = number_format($promotion['discount_value'], 2) . "%";
        } elseif ($promotion['discount_type'] === 'fixed_amount') {
            $discount_value_calc = $promotion['discount_value'];
            $discount_value_display = number_format($promotion['discount_value'], 2);
        } elseif ($promotion['discount_type'] === 'count_number') {
            $total_people = $number_of_guest;

            $price_per_person = $total_price / $total_people;
            $discount_people = $total_people - $pay_people;

            $discount_value_calc = $price_per_person * $discount_people;
            $discount_value_display = "มาจ่าย $pay_people จาก $total_people คน";
        }
    } else {
        // กรณีไม่มี promotion active
        $pay_people = 0;
        $discount_value_calc = 0;
        $discount_value_display = "ไม่มีส่วนลด";
    }
}



// คำนวณยอดสุทธิ
$total_amount = $total_price - $discount_value_calc;

?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ชำระเงิน</title>
    <link rel="stylesheet" href="../../CSS/stylepayment.css">
</head>

<body>
    <div class="background-frame">
        <div class="card">
            <h2>โต๊ะ <span id="table-number"><?php echo htmlspecialchars($table_id); ?></span></h2>
            <div class="info">
                <div class="row">
                    <p class="label">แพ็กเกจ :</p>
                    <p id="package-name" class="value"><?php echo htmlspecialchars($package_name); ?></p>
                    <p class="unit">บาท</p>
                </div>
                <div class="row">
                    <p class="label">จำนวนคน :</p>
                    <p id="people-count" class="value"><?php echo htmlspecialchars($number_of_guest); ?></p>
                    <p class="unit">คน</p>
                </div>
                <div class="row">
                    <p class="label">ราคารวม :</p>
                    <p id="total-price" class="value"><?php echo htmlspecialchars(number_format($total_price, 2)); ?></p>
                    <p class="unit">บาท</p>
                </div>
                <div class="row">
                    <p class="label">ส่วนลด :</p>
                    <p id="discount" class="value"><?php echo htmlspecialchars($discount_value_display); ?></p>
                    <p class="unit">บาท</p>
                </div>
                <div class="row">
                    <p class="label">ยอดสุทธิ :</p>
                    <p id="final-price" class="value"><?php echo htmlspecialchars(number_format($total_amount, 2)); ?></p>
                    <p class="unit">บาท</p>
                </div>
            </div>

            <div class="payment">
                <select id="payment-method" onchange="showPaymentOption()">
                    <option>เลือกวิธีชำระเงิน</option>
                    <option value="QR prompay">QR Promptpay</option>
                    <option value="credit">บัตรเครดิต/เดบิต</option>
                </select>
            </div>

            <div id="qr-code" style="display: none;">
                <img src="QR.jpg" alt="QR Promptpay" width="200">
            </div>
            <input type="hidden" id="getting-table-id" value="<?php echo htmlspecialchars($getting_table_id); ?>">

            <button class="confirm-btn" onclick="confirmPayment()">ยืนยัน</button>
        </div>
    </div>

    <script src="../../Javascript/payment.js"></script>
</body>

</html>