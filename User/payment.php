<?php
// เชื่อมต่อฐานข้อมูล
$servername = "localhost";
$username = "root";
$password = "123456";
$dbname = "winaishabu";

$conn = new mysqli($servername, $username, $password, $dbname);


// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
session_start();
if (isset($_SESSION['getting_table_id'])) {
    $table_id = $_SESSION['getting_table_id'];
    echo "Table ID: " . $table_id;
} else {
    echo "ไม่พบค่า getting_table_id";
}
// กำหนดหมายเลขโต๊ะ (สามารถรับจาก GET หรือ SESSION ได้)
$getting_table_id = isset($_GET['table_id']) ? $_GET['table_id'] : 3;

// ดึงข้อมูลการจอง
$sql = "SELECT 
            gt.getting_table_id,
            p.package_name,
            r.people_count,
            gt.total_amount AS total_price,
            promo.discount,
            (gt.total_amount - promo.discount) AS final_price
        FROM getting_table gt
        JOIN package_table p ON gt.package_id = p.package_id
        JOIN reservation_table r ON gt.reservation_id = r.reservation_id
        JOIN promotion_table promo ON gt.promotion_id = promo.promotion_id
        WHERE gt.getting_table_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $getting_table_id);
$stmt->execute();
$result = $stmt->get_result();

// ตรวจสอบว่ามีข้อมูลหรือไม่
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $packageName = $row["package_name"];
    $peopleCount = $row["people_count"];
    $totalPrice = $row["total_price"];
    $discount = $row["discount"];
    $finalPrice = $row["final_price"];
} else {
    $packageName = "ไม่พบข้อมูล";
    $peopleCount = 0;
    $totalPrice = 0;
    $discount = 0;
    $finalPrice = 0;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ชำระเงิน</title>
  <link rel="stylesheet" href="stylepayment.css">
</head>
<body>
  <div class="background-frame">
    <div class="card">
        <h2>โต๊ะ <span id="table-number"><?php echo htmlspecialchars($getting_table_id); ?></span></h2>
        <div class="info">
          <div class="row">
            <p class="label">แพ็กเกจ :</p>
            <p id="package-name" class="value"><?php echo htmlspecialchars($packageName); ?></p>
          </div>
          <div class="row">
            <p class="label">จำนวนคน :</p>
            <p id="people-count" class="value"><?php echo htmlspecialchars($peopleCount); ?></p>
            <p class="unit">คน</p>
          </div>
          <div class="row">
            <p class="label">ราคารวม :</p>
            <p id="total-price" class="value"><?php echo htmlspecialchars($totalPrice); ?></p>
            <p class="unit">บาท</p>
          </div>
          <div class="row">
            <p class="label">ส่วนลด :</p>
            <p id="discount" class="value"><?php echo htmlspecialchars($discount); ?></p>
            <p class="unit">บาท</p>
          </div>
          <div class="row">
            <p class="label">ยอดสุทธิ :</p>
            <p id="final-price" class="value"><?php echo htmlspecialchars($finalPrice); ?></p>
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

        <button class="confirm-btn" onclick="confirmPayment()">ยืนยัน</button>
    </div>
  </div>
  <script src="payment.js"></script>
</body>
</html>
