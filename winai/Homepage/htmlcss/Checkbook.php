<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['booking_data'] = [
        'first_name' => $_POST['first_name'],
        'last_name' => $_POST['last_name'],
        'number_of_guest' => $_POST['people'],
        'availability_id' => $_POST['availability_id'],
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

// ตั้งค่าภาษาไทยสำหรับวันที่
setlocale(LC_TIME, 'th_TH.UTF-8');
date_default_timezone_set('Asia/Bangkok');

// ฟังก์ชันแปลงชื่อเดือนเป็นภาษาไทย
function formatDateThai($date) {
    $thai_months = [
        "January" => "มกราคม",
        "February" => "กุมภาพันธ์",
        "March" => "มีนาคม",
        "April" => "เมษายน",
        "May" => "พฤษภาคม",
        "June" => "มิถุนายน",
        "July" => "กรกฎาคม",
        "August" => "สิงหาคม",
        "September" => "กันยายน",
        "October" => "ตุลาคม",
        "November" => "พฤศจิกายน",
        "December" => "ธันวาคม"
    ];

    $day = date("d", strtotime($date)); // วันที่
    $month = $thai_months[date("F", strtotime($date))]; // แปลงชื่อเดือนเป็นไทย
    $year = date("Y", strtotime($date)) + 543; // เปลี่ยน ค.ศ. เป็น พ.ศ.

    return "$day $month $year";
}

$thai_date = formatDateThai(date("Y-m-d")); // ใช้วันที่ปัจจุบัน
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
        <p><strong>วันที่:</strong> <?php echo $thai_date; ?></p> <!-- ✅ แสดงวันที่เป็น "วัน เดือน ปี" -->
        <p><strong>เวลา:</strong> <?php echo htmlspecialchars($booking['time']); ?></p>

        <form action="Booking.php" method="POST">
            <input type="hidden" name="first_name" value="<?php echo htmlspecialchars($booking['first_name']); ?>">
            <input type="hidden" name="last_name" value="<?php echo htmlspecialchars($booking['last_name']); ?>">
            <input type="hidden" name="people" value="<?php echo htmlspecialchars($booking['number_of_guest']); ?>">
            <input type="hidden" name="table" value="<?php echo htmlspecialchars($booking['table']); ?>">
            <input type="hidden" name="time" value="<?php echo htmlspecialchars($booking['time']); ?>">
            <input type="hidden" name="availability_id"
                value="<?php echo htmlspecialchars($booking['availability_id']); ?>">
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
