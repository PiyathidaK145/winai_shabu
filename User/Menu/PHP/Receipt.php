<?php
// รับค่าจาก URL ผ่าน $_GET
$getting_table_id = isset($_GET['getting_table_id']) ? $_GET['getting_table_id'] : 'ข้อมูลไม่ครบ';
$payment_method = isset($_GET['payment_method']) ? $_GET['payment_method'] : 'ข้อมูลไม่ครบ';
$total_payment = isset($_GET['total_payment']) ? $_GET['total_payment'] : 'ข้อมูลไม่ครบ';

// ตรวจสอบค่าที่ได้รับ
echo "<p>หมายเลขโต๊ะ: $getting_table_id</p>";
echo "<p>วิธีการชำระเงิน: $payment_method</p>";
echo "<p>ยอดชำระเงิน: ฿" . number_format($total_payment, 2) . "</p>";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - Winai's Shabu</title>
    <link rel="stylesheet" href="styleReceipt.css"> <!-- ลิงก์ไปยังไฟล์ CSS -->
</head>
<body>
    <div class="receipt-container">
        <div class="header">
            <h1>A's Shabu</h1>
            <p>ใบเสร็จ</p>
        </div>
        <div class="details">
            <p>หมายเลขโต๊ะ: <?php echo $getting_table_id; ?></p>
            <p>วิธีการชำระเงิน: <?php echo $payment_method; ?></p>
            <p>ยอดชำระเงิน: ฿<?php echo number_format($total_payment, 2); ?></p>
        </div>
    </div>
</body>
</html>
