<?php
// เชื่อมต่อกับฐานข้อมูล
$servername = "localhost";
$username = "root";
$password = "123456";
$dbname = "a_shabu";

$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
ini_set('display_errors', 1);
error_reporting(E_ALL);

// ตรวจสอบค่าจาก URL
if (isset($_GET['getting_table_id']) && isset($_GET['payment_method']) && isset($_GET['total_payment'])) {
    $getting_table_id = $_GET['getting_table_id'];
    $payment_method = $_GET['payment_method'];
    $total_payment = $_GET['total_payment'];

    $sql = "SELECT approve FROM payment_verificatio WHERE payment_id = ?"; // ใช้คอลัมน์ที่ถูกต้องที่นี่
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("i", $getting_table_id);
    if ($stmt->execute()) {
        // ดึงข้อมูลจากฐานข้อมูล
        $stmt->bind_result($approve_status);
        $stmt->fetch();

        // หากสถานะเป็น "Complete" จะเปลี่ยนข้อความในหน้า HTML
        if ($approve_status == 'completed') {
            $status_message = "ชำระเสร็จสิ้น";
            $hide_description = "none"; // ซ่อนข้อความ description
            $show_button = "block"; // แสดงปุ่มถัดไป
        } else {
            $status_message = "กำลังตรวจสอบการชำระเงิน...";
            $hide_description = "block"; // ให้แสดงข้อความ description
            $show_button = "none"; // ซ่อนปุ่มถัดไป
        }
    } else {
        die("Query failed: " . $stmt->error);
    }
    $stmt->close();
} else {
    echo "<script>alert('ข้อมูลการชำระเงินไม่สมบูรณ์');</script>";
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>หน้ารอการชำระเงิน</title>
    <link rel="stylesheet" href="Makepayment.css">
</head>

<body>
    <div class="container">
        <div class="circle-wrapper">
            <svg class="progress-circle" width="100" height="100">
                <circle class="circle-bg" cx="50" cy="50" r="40" />
                <circle class="circle-progress" cx="50" cy="50" r="40" />
            </svg>
            <div class="checkmark hidden">&#10004;</div>
        </div>
        <p class="success-text"><?php echo $status_message; ?></p>
        <p class="description" style="display: <?= $hide_description; ?>;">กรุณารอสักครู่
            ข้อมูลของคุณกำลังอยู่ในระหว่างการตรวจสอบจากทางร้าน</p>

        <!-- ปุ่มถัดไปจะแสดงเสมอ แต่เริ่มต้นเป็นสีเทาและกดไม่ได้ -->
        <div class="next-button" style="display: block; margin-top: 20px;">
            <button id="nextButton" disabled
                style="background-color: gray; color: white; border-radius: 10px; padding: 10px 50px; border: none; cursor: not-allowed;">
                ถัดไป
            </button>
        </div>
    </div>

    <script src="Makepayment.js"></script>
</body>

</html>