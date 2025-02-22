<?php
$servername = "localhost";
$username = "root";
$password = "123456";
$dbname = "a_shabu";

$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// รับค่า reservation_id จาก URL
$reservation_id = isset($_GET['reservation_id']) ? $_GET['reservation_id'] : null;

// แสดงค่า reservation_id
echo "reservation_id: $reservation_id"; // เพื่อตรวจสอบค่า

// ถ้ามี reservation_id
if ($reservation_id) {
    $sql = "SELECT table_id FROM table_availability WHERE availability_id = (SELECT availability_id FROM reservation WHERE reservation_id = ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("SQL prepare failed: " . $conn->error); // แสดงข้อผิดพลาดถ้าการเตรียม SQL ล้มเหลว
    }

    $stmt->bind_param("i", $reservation_id);
    $stmt->execute();

    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $table_id = $row["table_id"];
    } else {
        die("No data found for reservation_id: $reservation_id");
    }
} else {
    $table_id = null;
}

// ถ้าได้ table_id แล้วดึงข้อมูลแพ็คเกจ
if ($table_id) {
    $sql = "SELECT 
            gt.getting_table_id,
            gt.package_id,
            gt.reservation_id,
            p.package_name,
            p.price,
            r.people_count,
            gt.total_amount AS total_price,
            COALESCE(promo.discount, 0) AS discount,
            (gt.total_amount - COALESCE(promo.discount, 0)) AS final_price
          FROM getting_table gt
          INNER JOIN package p ON gt.package_id = p.package_id
          INNER JOIN reservation r ON gt.reservation_id = r.reservation_id
          LEFT JOIN promotion_table promo ON gt.promotion_id = promo.promotion_id
          WHERE gt.reservation_id = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $reservation_id); // ใช้ reservation_id ที่ได้จาก URL
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $packageID = $row["package_id"];
            $promotionID = $row["promotion_id"];
            $packageName = $row["package_name"];  // แสดงชื่อแพ็กเกจ
            $packagePrice = $row["price"];
            $peopleCount = $row["people_count"];
            $totalPrice = $row["total_price"];
            $discount = $row["discount"];
            $finalPrice = $row["final_price"];
        } else {
            $packageID = null;
            $promotionID = null;
            $packageName = "ไม่พบข้อมูล";
            $packagePrice = 0;
            $peopleCount = 0;
            $totalPrice = 0;
            $discount = 0;
            $finalPrice = 0;
        }
    } else {
        die("SQL prepare failed: " . $conn->error);
    }
} else {
    // ถ้าไม่ได้รับค่า table_id
    $packageID = null;
    $promotionID = null;
    $packageName = "ไม่มีข้อมูลการจอง";
    $packagePrice = 0;
    $peopleCount = 0;
    $totalPrice = 0;
    $discount = 0;
    $finalPrice = 0;
}
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
      <h2>โต๊ะ <span id="table-number"><?php echo htmlspecialchars($table_id ? $table_id : 'ไม่พบข้อมูล'); ?></span>
      </h2>
      <div class="info">
        <div class="row">
          <p class="label">แพ็กเกจ :</p>
          <p id="package-name" class="value"><?php echo htmlspecialchars($packageName); ?></p>
          <p class="unit">บาท</p>
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

      <div id="qr-code" style="display: none;">
        <img src="QR.jpg" alt="QR Promptpay" width="200">
      </div>

      <input type="hidden" id="package-id" value="<?php echo htmlspecialchars($packageID); ?>">
      <input type="hidden" id="promotion-id" value="<?php echo htmlspecialchars($promotionID); ?>">
      <input type="hidden" id="package-price" value="<?php echo htmlspecialchars($packagePrice); ?>">

      <button class="confirm-btn" onclick="confirmPayment()">ยืนยัน</button>
    </div>
  </div>

  <script src="payment.js"></script>
</body>

</html>
