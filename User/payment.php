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

        <button class="confirm-btn" onclick="confirmPayment()">ยืนยัน</button>
    </div>
  </div>
  <script src="payment.js"></script>
</body>
</html>