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
      <?php
      // Assuming data is fetched from a database or session
      $tableNumber = 3; // Example value
      $packageName = "ชื่อแพ็กเกจตัวอย่าง"; // Example value
      $peopleCount = 4; // Example value
      $totalPrice = 1000; // Example value
      $discount = 100; // Example value
      $finalPrice = $totalPrice - $discount; // Example calculation
      $sql = "SELECT 
            gt.getting_table_id,
            gt.package_id,  -- เพิ่ม package_id
            gt.promotion_id, -- เพิ่ม promotion_id
            p.package_name,
            p.price, -- ดึงราคาของแพ็คเกจ
            r.people_count,
            gt.total_amount AS total_price,
            promo.discount,
            (gt.total_amount - promo.discount) AS final_price
        FROM getting_table gt
        JOIN package_table p ON gt.package_id = p.package_id
        JOIN reservation_table r ON gt.reservation_id = r.reservation_id
        LEFT JOIN promotion_table promo ON gt.promotion_id = promo.promotion_id
        WHERE gt.getting_table_id = ?";

      $stmt = $conn->prepare($sql);
      $stmt->bind_param("i", $getting_table_id);
      $stmt->execute();
      $result = $stmt->get_result();

      // ตรวจสอบว่ามีข้อมูลหรือไม่
      if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $packageID = $row["package_id"];
        $promotionID = $row["promotion_id"];
        $packageName = $row["package_name"];
        $packagePrice = $row["price"]; // ดึงราคาของแพ็คเกจ
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

      ?>

      <h2>โต๊ะ <span id="table-number"><?php echo $tableNumber; ?></span></h2>
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
          <p id="total-price" class="value"><?php echo $totalPrice; ?></p>
          <p class="unit">บาท</p>
        </div>
        <div class="row">
          <p class="label">ส่วนลด :</p>
          <p id="discount" class="value"><?php echo $discount; ?></p>
          <p class="unit">บาท</p>
        </div>
        <div class="row">
          <p class="label">ยอดสุทธิ :</p>
          <p id="final-price" class="value"><?php echo $finalPrice; ?></p>
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