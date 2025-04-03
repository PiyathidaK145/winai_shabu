<?php
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
    $month = $thai_months[date("F", strtotime($date))]; // แปลงชื่อเดือนเป็นภาษาไทย
    $year = date("Y", strtotime($date)) + 543; // แปลง ค.ศ. เป็น พ.ศ.

    return "$day $month $year";
}

$thai_date = formatDateThai(date("Y-m-d")); // แปลงวันที่ปัจจุบัน
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ทำรายการสำเร็จ</title>
    <link rel="stylesheet" href="../../CSS/Dec_Successfully.css">
</head>

<body>
    <div class="success-box">
        <button class="close-btn" onclick="closeBox()">&times;</button>
        <h1>ทำรายการสำเร็จ</h1>

        <div class="table-info">
            <p>รหัสการจอง</p>
            <p id="booking-id">
                <?php echo isset($_GET['reservation_id']) ? htmlspecialchars($_GET['reservation_id']) : 'N/A'; ?>
            </p>
        </div>

        <div class="table-info">
            <p>หมายเลขโต๊ะ</p>
            <p id="table-number">
                <?php echo isset($_GET['table']) ? htmlspecialchars($_GET['table']) : 'N/A'; ?>
            </p>
        </div>

        <div class="table-info">
            <p>วันที่</p> <!-- ✅ แสดงวันที่เป็น "วัน เดือน ปี" -->
            <p id="date">
                <?php echo $thai_date; ?>
            </p>
        </div>

        <div class="table-info">
            <p>เวลา</p>
            <p id="time">
                <?php echo isset($_GET['time']) ? htmlspecialchars($_GET['time']) : 'N/A'; ?>
            </p>
        </div>

        <div class="note">
            <p>ควรมาก่อนเวลา 15 นาที</p>
            <p>และโปรดแคปหน้าจอเพื่อแสดงต่อพนักงาน</p>
        </div>
    </div>

    <script>
        // ฟังก์ชันเมื่อกดปุ่มปิด
        function closeBox() {
            // กลับไปที่หน้าแรก
            window.location.href = "../Home/Homepage.php"; // เปลี่ยนเป็น path ของหน้าแรก
        }
    </script>

</body>

</html>
