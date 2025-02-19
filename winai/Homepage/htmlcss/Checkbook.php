<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['booking_data'] = [
        'first_name' => $_POST['first_name'],
        'last_name' => $_POST['last_name'],
        'number_of_guest' => $_POST['people'],
        'availability_id' => $_POST['availability_id'], // ✅ เพิ่ม availability_id
        'table' => $_POST['table'],
        'time' => $_POST['time']
    ];
    header("Location: Checkbook.php");
    exit();
}


if (!isset($_SESSION['booking_data']) || empty($_SESSION['booking_data'])) {
    echo "<script>alert('ไม่มีข้อมูลการจอง!'); window.location.href='Homepage.php';</script>";
    exit();
}

$booking = $_SESSION['booking_data'];

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
// สมมติว่าข้อมูลการจองถูกส่งจากฟอร์มใน Checkbook.php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับค่าจากฟอร์ม
    $first_name = $_POST['first_name'];  // ชื่อลูกค้า
    $table = $_POST['table'];  // หมายเลขโต๊ะ
    $people = $_POST['people'];  // จำนวนคน
    $time = $_POST['time'];  // เวลา

    // เก็บข้อมูลลงใน session
    $_SESSION['booking_data'] = [
        'first_name' => $first_name,
        'table' => $table,
        'people' => $people,
        'time' => $time
    ];

    // เปลี่ยนเส้นทางไปที่ booking.php
    header("Location: booking.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Dec_checkbook.css">
    <title>หน้าตรวจสอบข้อมูลการจอง</title>
</head>

<body>
    <div class="container">
        <div class="header">โปรดตรวจสอบข้อมูลการจอง</div>
        <p><strong>ชื่อ:</strong> <?php echo htmlspecialchars($booking['first_name']); ?></p>
        <p><strong>นามสกุล:</strong> <?php echo htmlspecialchars($booking['last_name']); ?></p>
        <p><strong>จำนวนคน:</strong> <?php echo htmlspecialchars($booking['number_of_guest']); ?></p>
        <p><strong>หมายเลขโต๊ะ:</strong> <?php echo htmlspecialchars($booking['table']); ?></p>
        <p><strong>เวลา:</strong> <?php echo htmlspecialchars($booking['time']); ?></p>

        <form action="Booking.php" method="POST">
            <input type="hidden" name="first_name" value="<?php echo htmlspecialchars($booking['first_name']); ?>">
            <input type="hidden" name="last_name" value="<?php echo htmlspecialchars($booking['last_name']); ?>">
            <input type="hidden" name="people" value="<?php echo htmlspecialchars($booking['number_of_guest']); ?>">
            <input type="hidden" name="table" value="<?php echo htmlspecialchars($booking['table']); ?>">
            <input type="hidden" name="time" value="<?php echo htmlspecialchars($booking['time']); ?>">
            <input type="hidden" name="availability_id"
                value="<?php echo htmlspecialchars($booking['availability_id']); ?>"> <!-- ✅ เพิ่มตรงนี้ -->
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

            <div class="buttons">
                <button type="button" onclick="window.history.back();" class="button check">แก้ไข</button>
                <button type="submit" class="button confirm">ยืนยัน</button>
            </div>
        </form>


        <div class="note">
            <p><strong>* หมายเหตุ</strong></p>
            <p>ชื่อ + เบอร์จะเป็นรหัสสำหรับการยกเลิกโต๊ะ และกรุณาแคปหน้าจอการยืนยันสำเร็จ
                เพื่อแสดงหลักฐานการยืนยันกับทางร้าน</p>
        </div>
    </div>
</body>

</html>