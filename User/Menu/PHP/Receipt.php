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

    // ดึงสถานะ approve จากฐานข้อมูล
    $sql = "SELECT approve FROM payment_verificatio WHERE payment_id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("i", $getting_table_id);
    if ($stmt->execute()) {
        $stmt->bind_result($approve_status);
        $stmt->fetch();
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
    <title>สถานะการชำระเงิน</title>
    <link rel="stylesheet" href="Makepayment.css">
</head>
<body>
    <div class="container">
        <h2>รายละเอียดการชำระเงิน</h2>
        <p><strong>หมายเลขโต๊ะ:</strong> <?= htmlspecialchars($getting_table_id); ?></p>
        <p><strong>วิธีการชำระเงิน:</strong> <?= htmlspecialchars($payment_method); ?></p>
        <p><strong>ยอดชำระ:</strong> <?= number_format($total_payment, 2); ?> บาท</p>

        <!-- แสดงสถานะการชำระเงิน -->
        <p><strong>สถานะ:</strong> <?= ($approve_status == 'Complete') ? '<span style="color: green;">ชำระเสร็จสิ้น</span>' : '<span style="color: orange;">กำลังตรวจสอบ</span>'; ?></p>

        <!-- ปุ่มถัดไป -->
        <div class="next-button" style="margin-top: 20px;">
            <button id="nextButton" disabled
                style="background-color: gray; color: white; border-radius: 10px; padding: 10px 50px; border: none; cursor: not-allowed;">
                ถัดไป
            </button>
        </div>
    </div>

    <script>
        window.onload = function () {
            const nextButton = document.getElementById("nextButton");

            <?php if ($approve_status == 'Complete') : ?>
                // ถ้าชำระเงินเสร็จสิ้น ให้ปุ่มใช้งานได้
                nextButton.style.backgroundColor = "green";
                nextButton.style.cursor = "pointer";
                nextButton.removeAttribute("disabled");

                // เมื่อกดปุ่มให้ไปหน้า Receipt.php
                nextButton.addEventListener("click", function () {
                    window.location.href = `Receipt.php?getting_table_id=<?= urlencode($getting_table_id); ?>&payment_method=<?= urlencode($payment_method); ?>&total_payment=<?= urlencode($total_payment); ?>`;
                });
            <?php endif; ?>
        };
    </script>
</body>
</html>
