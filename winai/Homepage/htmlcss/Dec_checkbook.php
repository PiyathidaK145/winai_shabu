<?php
session_start();

// ตรวจสอบว่ามีข้อมูลจาก Checkbook.php หรือไม่
if (!isset($_SESSION['booking_data'])) {
    echo "<script>alert('ไม่มีข้อมูลการจอง!'); window.location.href='Homepage.php';</script>";
    exit();
}

$booking = $_SESSION['booking_data'];
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>หน้าตรวจสอบข้อมูลการจอง</title>
    <link rel="stylesheet" href="Dec_checkbook.css">
</head>

<body>
    <div class="container">
        <div class="header">โปรดตรวจสอบข้อมูลอีกครั้งก่อนทำการยืนยัน</div>
        <form action="Booking.php" method="POST">
            <div class="details">
                <p><strong>หมายเลขโต๊ะ:</strong> <?php echo htmlspecialchars($booking['table']); ?></p>
                <input type="hidden" name="table" value="<?php echo htmlspecialchars($booking['table']); ?>">
            </div>

            <div class="details">
                <p><strong>ชื่อ:</strong> <?php echo htmlspecialchars($booking['first_name']); ?></p>
                <input type="hidden" name="first_name" value="<?php echo htmlspecialchars($booking['first_name']); ?>">

                <p><strong>จำนวนคน:</strong> <?php echo htmlspecialchars($booking['people']); ?></p>
                <input type="hidden" name="people" value="<?php echo htmlspecialchars($booking['people']); ?>">

                <p><strong>เวลาในการจอง:</strong> <?php echo htmlspecialchars($booking['time']); ?></p>
                <input type="hidden" name="time" value="<?php echo htmlspecialchars($booking['time']); ?>">
            </div>

            <div class="buttons">
                <button type="button" onclick="window.history.back();" class="button check">แก้ไข</button>
                <button type="submit" class="button confirm">ยืนยัน</button>
            </div>
        </form>

        <div class="note">
            <p><strong>* หมายเหตุ</strong></p>
            <p>ชื่อ + เบอร์จะเป็นรหัสสำหรับการยกเลิกโต๊ะ และกรุณาแคปหน้าจอการยืนยันสำเร็จ เพื่อแสดงหลักฐานการยืนยันกับทางร้าน</p>
        </div>
    </div>
</body>
<script src="scriptCheckbook.js"></script>
</html>
