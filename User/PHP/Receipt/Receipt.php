<?php
include '../../../config/connect_db.php';
// เช็คค่าจาก URL
if (isset($_GET['getting_table_id']) && isset($_GET['payment_method']) && isset($_GET['total_payment'])) {
    $getting_table_id = $_GET['getting_table_id'];
    $payment_method = $_GET['payment_method'];
    $total_payment = $_GET['total_payment'];

    // ดึง reservation_id หรือ warkin_id จาก getting_table
    $sql = "SELECT reservation_id, walkin_id FROM getting_table WHERE getting_table_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $getting_table_id);
    $stmt->execute();
    $stmt->bind_result($reservation_id, $workin_id);
    $stmt->fetch();
    $stmt->close();

    // ตรวจสอบว่าควรใช้ reservation_id หรือ warkin_id
    $primary_id = $reservation_id ? $reservation_id : $workin_id;

    if ($primary_id) {
        // ดึง availability_id และ number_of_guest จาก reservation หรือ walkin โดยใช้ primary_id
        $sql2 = "
            SELECT availability_id, number_of_guest FROM reservation WHERE reservation_id = ?
            UNION DISTINCT
            SELECT availability_id, number_of_guest FROM walkin WHERE walkin_id = ?";
        
        $stmt2 = $conn->prepare($sql2);
        if (!$stmt2) {
            die('MySQL prepare error: ' . $conn->error);
        }
        $stmt2->bind_param("ii", $primary_id, $primary_id);
        $stmt2->execute();
        $stmt2->bind_result($availability_id, $number_of_guest); // ดึง availability_id และ number_of_guest
        if ($stmt2->fetch()) {
            // ถ้าพบข้อมูล
        } else {
            $availability_id = null;
            $number_of_guest = 0;
        }
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
        if ($stmt3->fetch()) {
            // ถ้าพบ table_id
        } else {
            $table_id = null;
        }
        $stmt3->close();
    }
    
    if ($primary_id) {
        // ใช้ primary_id เพื่อดึง custumer_id, first_name, และ last_name จากตาราง custumer ผ่าน reservation หรือ walkin
        $sql4 = "
            SELECT m.custumer_id, m.first_name, m.last_name
            FROM `custumer` m
            JOIN `reservation` r ON m.custumer_id = r.custumer_id
            WHERE r.reservation_id = ?
            UNION DISTINCT
            SELECT m.custumer_id, m.first_name, m.last_name
            FROM `custumer` m
            JOIN `walkin` w ON m.custumer_id = w.custumer_id
            WHERE w.walkin_id = ?";
        
        $stmt4 = $conn->prepare($sql4);
        if (!$stmt4) {
            die('MySQL prepare error: ' . $conn->error);
        }
        // ใช้ primary_id เป็นค่าใน parameter สำหรับทั้ง reservation_id และ walkin_id
        $stmt4->bind_param("ii", $primary_id, $primary_id); // "ii" สำหรับ int
        $stmt4->execute();
        $stmt4->bind_result($custumer_id, $first_name, $last_name); // ดึงข้อมูล custumer_id, first_name และ last_name
        if ($stmt4->fetch()) {
            // ถ้าพบข้อมูล
            // สามารถใช้งาน $custumer_id, $first_name, $last_name ได้ที่นี่
        } else {
            $custumer_id = null; // ถ้าไม่พบข้อมูล
            $first_name = null;
            $last_name = null;
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

    $promotion_id = isset($promotion_id) ? $promotion_id : 0;
    if (isset($getting_table_id)) {
        // ดึง promotion_id จาก getting_table โดยใช้ getting_table_id
        $sqlPromotion = "SELECT promotion_id FROM getting_table WHERE getting_table_id = ?";
        $stmtPromotion = $conn->prepare($sqlPromotion);
        if (!$stmtPromotion) {
            die('MySQL prepare error: ' . $conn->error);
        }
        $stmtPromotion->bind_param("i", $getting_table_id);
        $stmtPromotion->execute();
        $stmtPromotion->bind_result($promotion_id);
        if ($stmtPromotion->fetch()) {
            // promotion_id ถูกดึงมาแล้ว
        } else {
            $promotion_id = 0; // ถ้าไม่มี promotion_id
        }
        $stmtPromotion->close();
    }


    /// ใช้ promotion_id ดึง discount_value และ discount_type
    if ($promotion_id > 0) {
        $sql6 = "SELECT discount_value, discount_type FROM promotion_item WHERE promotion_id = ?";
        $stmt6 = $conn->prepare($sql6);
        if (!$stmt6) {
            die('MySQL prepare error: ' . $conn->error);
        }
        $stmt6->bind_param("i", $promotion_id);
        $stmt6->execute();
        $stmt6->bind_result($discount_value, $discount_type);
        $stmt6->fetch();
        $stmt6->close();
    } else {
        $discount_value = 0;
        $discount_type = "";
    }

    // ตรวจสอบค่า total_price ก่อนใช้งาน
    $total_price = isset($number_of_guest) ? ($number_of_guest * $price) : 0;

    // คำนวณ Total ตามประเภทของส่วนลด
    if ($discount_type === "percentage") {
        $Total = $total_price - ($total_price * ($discount_value / 100));
    } else {
        $Total = $total_price - $discount_value;
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
    $sqlEmployee = "SELECT employee_id FROM getting_table WHERE getting_table_id = ?";
    $stmtEmployee = $conn->prepare($sqlEmployee);
    if (!$stmtEmployee) {
        die('MySQL prepare error: ' . $conn->error);
    }
    $stmtEmployee->bind_param("i", $getting_table_id);
    $stmtEmployee->execute();
    $stmtEmployee->bind_result($employee_id);
    $stmtEmployee->fetch();
    $stmtEmployee->close();

    // ฟอร์แมต employee_id เป็น 00-000-X
    $employeeId = "00-000-" . str_pad($employee_id, 1, "0", STR_PAD_LEFT);


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
// ดึงเวลาเริ่มต้นจาก getting_table และเวลาสิ้นสุดจาก payment
$sqlTime = "SELECT 
DATE_FORMAT(g.created_at, '%H:%i') AS start_time, 
DATE_FORMAT(p.payment_timestamp, '%H:%i') AS end_time
FROM 
getting_table g
JOIN 
payment p ON g.getting_table_id = p.getting_table_id
WHERE 
g.getting_table_id = ? AND 
p.payment_method = ? AND 
p.total_payment = ?";

$stmtTime = $conn->prepare($sqlTime);
if (!$stmtTime) {
    die('MySQL prepare error: ' . $conn->error);
}
$stmtTime->bind_param("isi", $getting_table_id, $payment_method, $total_payment);
$stmtTime->execute();
$stmtTime->bind_result($start_time, $end_time);

// ดึงข้อมูลเวลา
if ($stmtTime->fetch()) {
    // เก็บค่าเวลา
} else {
    die('ไม่พบข้อมูลเวลา');
}
$stmtTime->close();
// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - A's Shabu</title>
    <link rel="stylesheet" href="../../CSS/styleReceipt.css"> <!-- ลิงก์ไปยังไฟล์ CSS -->
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
            $dateTime = date("d/m/y H:i:s");
            $total_price = $number_of_guest * $price;
            $promotion_id = isset($promotion_id) ? $promotion_id : 0;
            $vatable = $total_price / 1.07;
            $vat = $total_price - $vatable; // คำนวณ VAT
            ?>
            <p><?php echo date("d/m/y"); ?></p> <!-- แสดงแค่วันที่ -->
            <p>เวลาเริ่ม: <?php echo $start_time; ?></p>
            <p>เวลาสิ้นสุด: <?php echo $end_time; ?></p>
        </div>
        <div class="employee-id">
            <p>พนักงาน: <?php echo $employeeId; ?></p>
            <p>รหัสใบเสร็จ: <?php echo  $receipt_id; ?></p>
        </div>
        <div class="additional-details">
            <p>รหัสสมาชิก: <?php echo  $custumer_id; ?></p>
        </div>
        <div class="additional-details">
            <p>ชื่อ: <?php echo  $first_name; ?></p>
            <p>นามสกุล: <?php echo  $last_name; ?></p>
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
            <p>
                <?php
                if ($discount_type === "percentage") {
                    echo number_format($discount_value, decimals: 2) . "%";
                } else {
                    echo number_format($discount_value, decimals: 2);
                }
                ?>
            </p>
        </div>
        <div class="item-total">
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
        <script src="../../Javascript/jsreceipt.js"></script>
        <script type="text/javascript">
            var receipt_id = <?php echo json_encode($receipt_id); ?>; // ส่งค่า PHP ไปยัง JavaScript
        </script>
</body>

</html>