<?php
// เชื่อมต่อฐานข้อมูล
$servername = "localhost";
$username = "root";
$password = "123456";
$dbname = "a_shabu";

$conn = new mysqli($servername, $username, $password, $dbname);
// เช็คการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "";
}
// เช็คค่าจาก URL
if (isset($_GET['getting_table_id']) && isset($_GET['payment_method']) && isset($_GET['total_payment'])) {
    $getting_table_id = $_GET['getting_table_id'];
    $payment_method = $_GET['payment_method'];
    $total_payment = $_GET['total_payment'];

    // ดึง reservation_id จาก getting_table_id
    $sql = "SELECT reservation_id FROM getting_table WHERE getting_table_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $getting_table_id);
    $stmt->execute();
    $stmt->bind_result($reservation_id);
    $stmt->fetch();
    $stmt->close();

    if ($reservation_id) {
        // ดึง availability_id และ number_of_guest จาก reservation โดยใช้ reservation_id
        $sql2 = "SELECT availability_id, number_of_guest FROM reservation WHERE reservation_id = ?";
        $stmt2 = $conn->prepare($sql2);
        if (!$stmt2) {
            die('MySQL prepare error: ' . $conn->error);
        }
        $stmt2->bind_param("i", $reservation_id);
        $stmt2->execute();
        $stmt2->bind_result($availability_id, $number_of_guest); // ดึง number_of_guest มาพร้อมกัน
        $stmt2->fetch();
        $stmt2->close();
    }
    if ($availability_id) {
        // ดึง table_id จาก table_availability โดยใช้ availability_id
        $sql3 = "SELECT table_id FROM table_availability WHERE availability_id = ?";
        $stmt3 = $conn->prepare($sql3);
        if (!$stmt3) {
            die('MySQL prepare error: ' . $conn->error);
        }
        $stmt3->bind_param("i", $availability_id);
        $stmt3->execute();
        $stmt3->bind_result($table_id);
        $stmt3->fetch();
        $stmt3->close();
    }
    if ($reservation_id) {
        // ใช้ reservation_id เพื่อดึง first_name และ member_id
        $sql4 = "SELECT m.member_id 
         FROM `member` m 
         JOIN `reservation` r ON m.first_name = r.first_name
         WHERE r.reservation_id = ?";

        $stmt4 = $conn->prepare($sql4);
        if (!$stmt4) {
            die('MySQL prepare error: ' . $conn->error);
        }
        $stmt4->bind_param("i", $reservation_id); // "i" สำหรับ int
        $stmt4->execute();
        $stmt4->bind_result($member_id);
        if ($stmt4->fetch()) {
            // member_id ถูกดึงมาแล้ว สามารถใช้ได้
        } else {
            $member_id = null; // ถ้าไม่มีข้อมูล
        }
        $stmt4->close();
    }

    if ($getting_table_id) {
        // ใช้ getting_table_id เพื่อดึง package_name และ price
        $sql5 = "SELECT p.package_name, p.price 
                 FROM getting_table g
                 JOIN package p ON g.package_id = p.package_id
                 WHERE g.getting_table_id = ?";

        $stmt5 = $conn->prepare($sql5);
        if (!$stmt5) {
            die('MySQL prepare error: ' . $conn->error);
        }
        $stmt5->bind_param("i", $getting_table_id);
        $stmt5->execute();
        $stmt5->bind_result($package_name, $price); // ดึง price มาพร้อมกับ package_name
        if ($stmt5->fetch()) {
            // package_name และ price ถูกดึงมาแล้ว
        } else {
            $package_name = "ไม่มีข้อมูล"; // ถ้าไม่มีข้อมูล
            $price = 0;
        }
        $stmt5->close();
    }
    // ใช้ promotion_id ดึง discount_value
    if ($promotion_id > 0) {
        $sql6 = "SELECT discount_value FROM promotion_item WHERE promotion_id = ?";
        $stmt6 = $conn->prepare($sql6);
        if (!$stmt6) {
            die('MySQL prepare error: ' . $conn->error);
        }
        $stmt6->bind_param("i", $promotion_id);
        $stmt6->execute();
        $stmt6->bind_result($discount_value);
        $stmt6->fetch();
        $stmt6->close();
    } else {
        $discount_value = 0; // ถ้า promotion_id = 0 ให้ discount_value = 0
    }

    $sqlPaymentId = "SELECT payment_id FROM payment WHERE getting_table_id = ?";
    $stmtPaymentId = $conn->prepare($sqlPaymentId);
    if (!$stmtPaymentId) {
        die('MySQL prepare error: ' . $conn->error);
    }
    $stmtPaymentId->bind_param("i", $getting_table_id);
    $stmtPaymentId->execute();
    $stmtPaymentId->bind_result($payment_id);

    // ตรวจสอบว่ามีข้อมูลหรือไม่
    if ($stmtPaymentId->fetch()) {
        // payment_id ถูกดึงมาแล้ว
    } else {
        die('ไม่พบ payment_id สำหรับ getting_table_id: ' . $getting_table_id);
    }

    $stmtPaymentId->close();

    // ดึง payment_verification_id จากตาราง payment_verification โดยใช้ payment_id
    $sqlPaymentVerification = "SELECT payment_verification_id FROM payment_verificatio WHERE payment_id = ?";
    $stmtPaymentVerification = $conn->prepare($sqlPaymentVerification);
    if (!$stmtPaymentVerification) {
        die('MySQL prepare error: ' . $conn->error);
    }
    $stmtPaymentVerification->bind_param("i", $payment_id);
    $stmtPaymentVerification->execute();
    $stmtPaymentVerification->bind_result($payment_verification_id);

    // ตรวจสอบว่ามีข้อมูลหรือไม่
    if ($stmtPaymentVerification->fetch()) {
        // payment_verification_id ถูกดึงมาแล้ว
    } else {
        die('ไม่พบ payment_verification_id สำหรับ payment_id: ' . $payment_id);
    }

    $stmtPaymentVerification->close();

    $receipt_id = rand(100000, 999999);

    // สมมติว่า employee_id ได้รับจาก POST หรือข้อมูลที่มีอยู่
    $employee_id = htmlspecialchars($_POST['employeeId']);  // ค่าจาก POST

    // สร้างคำสั่ง SQL สำหรับแทรกข้อมูลลงในตาราง receipt
    $sqlInsert = "INSERT INTO receipt (receipt_id, payment_verification_id, employee_id) 
              VALUES (?, ?, ?)";

    // เตรียมคำสั่ง SQL และทำการ bind parameters
    $stmtInsert = $conn->prepare($sqlInsert);
    if (!$stmtInsert) {
        die('MySQL prepare error: ' . $conn->error);
    }
    $stmtInsert->bind_param("iii", $receipt_id, $payment_verification_id, $employee_id);

    // Execute the query
    $stmtInsert->execute();

    // ตรวจสอบการแทรกข้อมูลสำเร็จหรือไม่
    if ($stmtInsert->affected_rows > 0) {
        echo "";
    } else {
        echo "ไม่สามารถบันทึกข้อมูลใบเสร็จ";
    }

    // ปิด statement
    $stmtInsert->close();
}

// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - A's Shabu</title>
    <link rel="stylesheet" href="styleReceipt.css"> <!-- ลิงก์ไปยังไฟล์ CSS -->
</head>

<body>
    <div class="receipt-container">
        <!-- ปุ่มปิด -->
        <button class="close-btn" onclick="closeReceipt()">×</button>
        <div class="header">
            <h1>A's Shabu</h1>
            <p>ใบเสร็จ</p>
        </div>
        <div class="details">
            <!-- แสดงข้อมูลที่ได้จากฐานข้อมูล -->
            <?php
            date_default_timezone_set('Asia/Bangkok');
            $employeeId = isset($_POST['employeeId']) ? htmlspecialchars($_POST['employeeId']) : "00-000-0";
            $dateTime = date("d/m/y H:i:s");
            $total_price = $number_of_guest * $price;
            $Total = $price * $number_of_guest - $discount_value;
            $vatable = $total_price / 1.07;
            $vat = $total_price - $vatable; // คำนวณ VAT
            ?>
            <p>พนักงาน: <?php echo $employeeId; ?></p>
            <p><?php echo $dateTime; ?></p>
        </div>
        <div class="additional-details">
            <p>รหัสใบเสร็จ: <?php echo "RCPT" . $receipt_id; ?></p>
        </div>
        <div class="additional-details">
            <p>รหัสลูกค้า: <?php echo $member_id; ?></p>
            <p>โต๊ะ: <?php echo $table_id; ?></p>
        </div>
        <div class="line"></div>
        <div class="item">
            <p><?php echo $package_name; ?></p>
            <p>
                <?php
                echo $number_of_guest . ' @ ' . number_format($price, 2) . ' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   '
                    . number_format($total_price, 2);
                ?>
            </p>
        </div>
        <div class="line"></div>
        <div class="item">
            <p>SubTotal</p>
            <p></p>
            <p><?php echo number_format($total_price, 2); ?></p>
        </div>
        <div class="item">
            <p>Discount</p>
            <p></p>
            <p><?php echo number_format($discount_value, 2); ?></p>
        </div>
        <div class="item">
            <p>Total</p>
            <p></p>
            <p><?php echo number_format($Total, 2); ?></p>
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
            <p><?php echo $payment_method . "<br>"; ?></p>
            <p></p>
            <p><?php echo number_format($Total, 2); ?></p>
        </div>
        <p class="vat-included">VAT INCLUDED</p>
        <div class="line"></div>
        <div class="footer">
            <p>Thank You</p>
        </div>
        <script src="jsreceipt.js"></script>
        <script type="text/javascript">
            var receipt_id = <?php echo json_encode($receipt_id); ?>; // ส่งค่า PHP ไปยัง JavaScript
        </script>
</body>

</html>