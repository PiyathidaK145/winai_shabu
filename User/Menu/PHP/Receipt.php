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
        <!-- ปุ่มปิด -->
        <button class="close-btn" onclick="closeReceipt()">×</button>
        <div class="header">
            <h1>Winai's Shabu</h1>
            <p>ใบเสร็จ</p>
        </div>
        <div class="details">
            <?php
            // ตัวอย่างข้อมูลจากเซิร์ฟเวอร์ (อาจดึงมาจากฐานข้อมูล)
            $employeeId = isset($_POST['employeeId']) ? htmlspecialchars($_POST['employeeId']) : "00-000-0";
            $dateTime = date("d/m/y H:i:s");
            $receiptCode = isset($_POST['receiptCode']) ? htmlspecialchars($_POST['receiptCode']) : "RCPT123456";
            //$customerCode = isset($_POST['customerCode']) ? htmlspecialchars($_POST['customerCode']) : "CUST001";
            $tableNumber = isset($_POST['tableNumber']) ? (int)$_POST['tableNumber'] : 10;
            $items = [
                ['name' => 'Mix Pakage ', 'quantity' => 2, 'price' => 499.00, 'total' => 998.00],
            ];
            $subTotal = array_sum(array_column($items, 'total'));
            //$specialDisc = 0.00;
            $vatable = $subTotal / 1.07; // คำนวณยอดที่คิด VAT
            $vat = $subTotal - $vatable; // คำนวณ VAT
            $total = $subTotal;
            ?>
            <p>พนักงาน: <?php echo $employeeId; ?></p>
            <p><?php echo $dateTime; ?></p>
        </div>
        <div class="additional-details">
            <p>รหัสใบเสร็จ: <?php echo $receiptCode; ?></p>
            <!--p>รหัสลูกค้า: <?php echo $customerCode; ?></p-->
        </div>
        <div class="additional-details">
            <p>สมาชิก: <?php echo $employeeId; ?></p>
            <p>โต๊ะ: <?php echo $tableNumber; ?></p>
        </div>
        <div class="line"></div>
        <?php foreach ($items as $item): ?>
        <div class="item">
            <p><?php echo $item['name']; ?></p>
            <p><?php echo $item['quantity'] . ' @' . number_format($item['price'], 2); ?></p>
            <p><?php echo number_format($item['total'], 2); ?></p>
        </div>
        <?php endforeach; ?>
        <div class="line"></div>
        <div class="item">
            <p>SubTotal</p>
            <p></p>
            <p><?php echo number_format($subTotal, 2); ?></p>
        </div>
        <!--div class="item">
            <p>Special Disc</p>
            <p></p>
            <p><?php /* echo number_format($specialDisc, 2);*/ ?></p>
        </div-->
        <div class="item">
            <p>Total</p>
            <p></p>
            <p><?php echo number_format($total, 2); ?></p>
        </div>
        <div class="item">
            <p>Vatable</p>
            <p></p>
            <p><?php echo number_format($vatable, 2); ?></p>
        </div>
        <div class="item">
            <p>VAT</p>
            <p></p>
            <p><?php echo number_format($vat, 2); ?></p>
        </div>
        <div class="item">
            <p>QR PromptPay</p>
            <p></p>
            <p><?php echo number_format($total, 2); ?></p>
        </div>
        <p class="vat-included">VAT INCLUDED</p>
        <div class="line"></div>
        <div class="footer">
            <p>Thank You</p>
        </div>
    </div>

    <script src="jsreceipt.js"></script>

    <!--script>
        function closeReceipt() {
            window.location.href = "reviews/review.php"; // ไปยังหน้าฟอร์มรีวิว
        }
    </script-->
</body>
</html>