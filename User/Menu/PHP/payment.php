<?php
$servername = "localhost";
$username = "root";
$password = "123456";
$dbname = "a_shabu";


try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// รับค่า getting_table_id, package_id และ reservation_id
$gettingTableId = isset($_POST['getting_table_id']) ? $_POST['getting_table_id'] : null;
$packageId = isset($_POST['package_id']) ? $_POST['package_id'] : null;
$reservationId = isset($_GET['reservation_id']) ? $_GET['reservation_id'] : null;

if (!$reservationId) {
    die("reservation_id is required");
}

$promotionId = null;
$packagePrice = 0;

if ($gettingTableId && $packageId) {
    // SQL Query เพื่อดึงข้อมูล promotion_id และ package price
    $query = "
        SELECT 
            gt.promotion_id,  -- ดึง promotion_id
            p.price AS package_price  -- ดึง price จาก package
        FROM 
            getting_table gt
        JOIN 
            package p ON gt.package_id = p.package_id
        WHERE 
            gt.getting_table_id = :getting_table_id AND p.package_id = :package_id
    ";

    // เตรียมและ execute query
    $stmt = $pdo->prepare($query);
    $stmt->execute(['getting_table_id' => $gettingTableId, 'package_id' => $packageId]);

    // ดึงข้อมูล
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // ตรวจสอบผลลัพธ์และเก็บค่า
    if ($result) {
        $promotionId = $result['promotion_id'];
        $packagePrice = $result['package_price'];
    }
}

// SQL Query เพื่อดึงข้อมูล reservation และ package ที่เกี่ยวข้อง
$query = "
    SELECT 
        gt.getting_table_id,  -- ดึง getting_table_id
        ta.table_id,
        p.package_name AS package_name, 
        p.price AS price,
        r.number_of_guest AS number_of_guest,
        (r.number_of_guest * p.price) AS total_price,
        0 AS discount_value,  -- ไม่มีส่วนลด
        (r.number_of_guest * p.price) AS total_amount
    FROM 
        getting_table gt
    JOIN 
        package p ON gt.package_id = p.package_id
    JOIN 
        reservation r ON gt.reservation_id = r.reservation_id
    JOIN 
        table_availability ta ON r.availability_id = ta.availability_id
    WHERE 
        gt.reservation_id = :reservation_id
    LIMIT 1
";

// เตรียมและ execute query
$stmt = $pdo->prepare($query);
$stmt->execute(['reservation_id' => $reservationId]);

// ดึงข้อมูล
$result = $stmt->fetch(PDO::FETCH_ASSOC);

// ตรวจสอบผลลัพธ์และแสดงข้อมูล
if ($result) {
    $getting_table_id = $result['getting_table_id'];  // ดึงค่า getting_table_id
    $table_id = $result['table_id'];
    $package_name = $result['package_name'];
    $price = $result['price'];
    $number_of_guest = $result['number_of_guest'];
    $total_price = $result['total_price'];
    $discount_value = $result['discount_value'];
    $total_amount = $result['total_amount'];

    // เพิ่มข้อมูล promotion_id และ package_price
    $total_price += $packagePrice; // ถ้าต้องการใช้ packagePrice ใน total_price
    // คุณสามารถใช้ $promotionId ได้ที่นี่ตามที่ต้องการ
} else {
    $getting_table_id = null;
    $table_id = null;
    $package_name = "ไม่มีข้อมูล";
    $price = 0;
    $number_of_guest = 0;
    $total_price = 0;
    $discount_value = 0;
    $total_amount = 0;
}

// คุณสามารถใช้ค่า $promotionId และ $packagePrice ที่เก็บไว้ได้ในที่อื่นๆ ในโค้ดของคุณ

?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ชำระเงิน</title>
    <link rel="stylesheet" href="stylepayment.css">
</head>

<body>
    <div class="background-frame">
        <div class="card">
            <h2>โต๊ะ <span
                    id="table-number"><?php echo htmlspecialchars($table_id ? $table_id : 'ไม่พบข้อมูล'); ?></span></h2>
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
                    <p id="total-price" class="value"><?php echo htmlspecialchars(number_format($total_price)); ?></p>
                    <p class="unit">บาท</p>
                </div>
                <div class="row">
                    <p class="label">ส่วนลด :</p>
                    <p id="discount" class="value"><?php echo htmlspecialchars(number_format($discount_value)); ?></p>
                    <p class="unit">บาท</p>
                </div>
                <div class="row">
                    <p class="label">ยอดสุทธิ :</p>
                    <p id="final-price" class="value"><?php echo htmlspecialchars(number_format($total_amount)); ?></p>
                    <p class="unit">บาท</p>
                </div>
            </div>

            <div class="payment">
                <select id="payment-method" onchange="showPaymentOption()">
                    <option>เลือกวิธีชำระเงิน</option>
                    <option value="qr">QR Promptpay</option>
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

    <script src="payment.js"></script>
</body>

</html>