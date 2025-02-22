<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

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
    echo "Connected successfully!";
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
        // ดึง availability_id จาก reservation โดยใช้ reservation_id
        $sql2 = "SELECT availability_id FROM reservation WHERE reservation_id = ?";
        $stmt2 = $conn->prepare($sql2);
        if (!$stmt2) {
            die('MySQL prepare error: ' . $conn->error);
        }
        $stmt2->bind_param("i", $reservation_id);
        $stmt2->execute();
        $stmt2->bind_result($availability_id);
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
    if ($first_name) {
        // ใช้ first_name ไปดึง member_id จาก member
        $sql4 = "SELECT member_id FROM member WHERE first_name = ?";
        $stmt4 = $conn->prepare($sql4);
        if (!$stmt3) {
            die('MySQL prepare error: ' . $conn->error);
        }        
        $stmt4->bind_param("s", $first_name); // ใช้ "s" สำหรับ string
        $stmt4->execute();
        $stmt4->bind_result($member_id);
        $stmt4->fetch();
        $stmt4->close();
    }
}

echo "Reservation ID: " . $reservation_id . "<br>";
echo "Table ID: " . $table_id . "<br>";
echo "Member Info: " . $member_id . "<br>"; // แสดงข้อมูลของสมาชิก

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
            $receipt_id = "RCPT" . rand(100000, 999999);
            ?> 
            <p>พนักงาน: <?php echo $employeeId; ?></p>
            <p><?php echo $dateTime; ?></p>
            </div>
            <div class="additional-details">
                <p>รหัสใบเสร็จ: <?php echo $receipt_id; ?></p>



            </div>
        </div>

        <script src="jsreceipt.js"></script>
</body>

</html>