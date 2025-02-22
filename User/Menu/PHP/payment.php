<?php
// เชื่อมต่อฐานข้อมูล
$host = "localhost";
$user = "root";
$password = "123456"; // เปลี่ยนเป็นรหัสผ่านของ MySQL
$database = "a_shabu";

$conn = new mysqli($host, $user, $password, $database);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("❌ การเชื่อมต่อล้มเหลว: " . $conn->connect_error);
}

// ตั้งค่าภาษาไทย
mysqli_set_charset($conn, "utf8");

// ตรวจสอบว่ามีค่า getting_table_id หรือไม่
if (isset($_GET['getting_table_id'])) {
    $getting_table_id = intval($_GET['getting_table_id']); // แปลงให้เป็นตัวเลข
} else {
    die("❌ ไม่พบค่า getting_table_id");
}

// ดึงข้อมูลจากฐานข้อมูล
$sql = "SELECT 
            gt.getting_table_id,
            gt.package_id,
            gt.promotion_id,
            p.package_name,
            p.price,
            r.people_count,
            gt.total_amount AS total_price,
            COALESCE(promo.discount, 0) AS discount,
            (gt.total_amount - COALESCE(promo.discount, 0)) AS final_price
        FROM getting_table gt
        JOIN package_table p ON gt.package_id = p.package_id
        JOIN reservation_table r ON gt.reservation_id = r.reservation_id
        LEFT JOIN promotion_table promo ON gt.promotion_id = promo.promotion_id
        WHERE gt.getting_table_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $getting_table_id);
$stmt->execute();
$result = $stmt->get_result();

// ตั้งค่าตัวแปรเริ่มต้น
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $packageID = $row["package_id"];
    $promotionID = $row["promotion_id"];
    $packageName = $row["package_name"];
    $packagePrice = $row["price"];
    $peopleCount = $row["people_count"];
    $totalPrice = $row["total_price"];
    $discount = $row["discount"];
    $finalPrice = $row["final_price"];
} else {
    die("❌ ไม่พบข้อมูลในระบบ");
}

// ปิดการเชื่อมต่อ
$stmt->close();
$conn->close();
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
            <h2>โต๊ะ <span id="table-number"><?php echo $getting_table_id; ?></span></h2>
            <div class="info">
                <div class="row">
                    <p class="label">แพ็กเกจ :</p>
                    <p id="package-name" class="value"><?php echo $packageName; ?></p>
                    <p class="unit">บาท</p>
                </div>
                <div class="row">
                    <p class="label">จำนวนคน :</p>
                    <p id="people-count" class="value"><?php echo $peopleCount; ?></p>
                    <p class="unit">คน</p>
                </div>
                <div class="row">
                    <p class="label">ราคารวม :</p>
                    <p id="total-price" class="value"><?php echo number_format($totalPrice, 2); ?></p>
                    <p class="unit">บาท</p>
                </div>
                <div class="row">
                    <p class="label">ส่วนลด :</p>
                    <p id="discount" class="value"><?php echo number_format($discount, 2); ?></p>
                    <p class="unit">บาท</p>
                </div>
                <div class="row">
                    <p class="label">ยอดสุทธิ :</p>
                    <p id="final-price" class="value"><?php echo number_format($finalPrice, 2); ?></p>
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

            <!-- พื้นที่สำหรับแสดงคิวอาร์โค้ด -->
            <div id="qr-code" style="display: none;">
                <img src="QR.jpg" alt="QR Promptpay" width="200">
            </div>
            
            <!-- Hidden Inputs เพื่อเก็บค่า -->
            <input type="hidden" id="package-id" value="<?php echo htmlspecialchars($packageID); ?>">
            <input type="hidden" id="promotion-id" value="<?php echo htmlspecialchars($promotionID); ?>">
            <input type="hidden" id="package-price" value="<?php echo htmlspecialchars($packagePrice); ?>">

            <button class="confirm-btn" onclick="confirmPayment()">ยืนยัน</button>
        </div>
    </div>
    <script src="payment.js"></script>
</body>
</html>
