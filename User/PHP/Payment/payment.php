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

// รับค่า reservation_id
$reservationId = isset($_GET['reservation_id']) ? $_GET['reservation_id'] : null;
if (!$reservationId) {
    die("reservation_id is required");
}

// ดึงข้อมูล getting_table
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
$result = $stmt->fetch(PDO::FETCH_ASSOC);

// ตรวจสอบว่ามีข้อมูลหรือไม่
if ($result) {
    $getting_table_id = $result['getting_table_id'];
    $promotionId = $result['promotion_id'];
    $table_id = $result['table_id'];
    $package_name = $result['package_name'];
    $price = $result['price'];
    $number_of_guest = $result['number_of_guest'];
    $total_price = $result['total_price'];
} else {
    die("ไม่พบข้อมูลการจอง");
}

// ค่าเริ่มต้นของส่วนลด
$discount_value_display = "0"; // ใช้สำหรับแสดงผล
$discount_value_calc = 0; // ใช้คำนวณยอดสุทธิ

// ตรวจสอบว่ามีโปรโมชันหรือไม่
if ($promotionId) {
    $query = "
        SELECT discount_type, discount_value
        FROM promotion_item
        WHERE promotion_id = :promotion_id 
            AND status = 'active' 
            AND CURDATE() BETWEEN start_date AND end_date
        LIMIT 1
    ";

    $stmt = $pdo->prepare($query);
    $stmt->execute(['promotion_id' => $promotionId]);
    $promotion = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($promotion) {
        if ($promotion['discount_type'] === 'percentage') {
            $discount_value_calc = ($total_price * $promotion['discount_value']) / 100;
            $discount_value_display = number_format($promotion['discount_value'], 2) . "%";
        } elseif ($promotion['discount_type'] === 'fixed_amount') {
            $discount_value_calc = $promotion['discount_value'];
            $discount_value_display = number_format($promotion['discount_value'], 2);
        }
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
