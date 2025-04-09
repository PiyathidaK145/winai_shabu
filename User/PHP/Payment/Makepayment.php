<?php
include '../../../config/connect_db.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (isset($_GET['getting_table_id']) && isset($_GET['payment_method']) && isset($_GET['total_payment'])) {
    $getting_table_id = $_GET['getting_table_id'];
    $payment_method = $_GET['payment_method'];
    $total_payment = $_GET['total_payment'];

    // 🔍 ดึง payment_id จากตาราง payment
    $sql_payment = "SELECT payment_id FROM payment WHERE getting_table_id = ? AND total_payment = ?";
    $stmt_payment = $conn->prepare($sql_payment);
    if (!$stmt_payment) {
        die("Error preparing payment statement: " . $conn->error);
    }
    $stmt_payment->bind_param("id", $getting_table_id, $total_payment);
    $stmt_payment->execute();
    $stmt_payment->bind_result($payment_id);
    $stmt_payment->fetch();
    $stmt_payment->close();

    if (!$payment_id) {
        die("ไม่พบข้อมูลการชำระเงินที่ตรงกับ getting_table_id และ total_payment");
    }

    // 🔍 ดึง approve จากตาราง payment_verificatio
    $sql_verify = "SELECT approve FROM payment_verificatio WHERE payment_id = ?";
    $stmt_verify = $conn->prepare($sql_verify);
    if (!$stmt_verify) {
        die("Error preparing verification statement: " . $conn->error);
    }
    $stmt_verify->bind_param("i", $payment_id);
    $stmt_verify->execute();
    $stmt_verify->bind_result($approve_status);
    $stmt_verify->fetch();
    $stmt_verify->close();

    // ✅ ตรวจสอบสถานะการ approve
    if ($approve_status == 'completed') {
        $status_message = "ชำระเสร็จสิ้น";
        $hide_description = "none"; // ซ่อนข้อความ description
        $show_button = "block"; // แสดงปุ่มถัดไป
    } elseif ($approve_status == 'failed') {
        header("Location: ../Payment/payment.php");
        exit(); // จบการทำงาน
    } else {
        $status_message = "กำลังตรวจสอบการชำระเงิน...";
        $hide_description = "block"; // ให้แสดงข้อความ description
        $show_button = "none"; // ซ่อนปุ่มถัดไป
    }

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
    <link rel="stylesheet" href="../../CSS/Makepayment.css">
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
                style="background-color: gray; color: white; border-radius: 10px; padding: 10px 50px; border: none; cursor: not-allowed;" >
                ถัดไป
            </button>
        </div>
    </div>

    <script src="../../Javascript/Makepayment.js" defer></script>
</body>

</html>