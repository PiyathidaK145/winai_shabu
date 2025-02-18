<?php
// ดึงค่าจาก URL
$table = isset($_GET['table']) ? htmlspecialchars($_GET['table']) : '';
$time = isset($_GET['time']) ? htmlspecialchars($_GET['time']) : '';
$availability_id = isset($_GET['availability_id']) ? htmlspecialchars($_GET['availability_id']) : '';
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ฟอร์มการจอง</title>
    <link rel="stylesheet" href="stylesForm.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div class="form-container">
        <h1>ฟอร์มการจอง</h1>
        <a class="close-button" href="javascript:void(0);" onclick="goBack()">&times;</a>

        <form id="bookingForm" action="Checkbook.php" method="POST">
            <input type="hidden" id="table" name="table" value="<?php echo $table; ?>">
            <input type="hidden" id="availability_id" name="availability_id" value="<?php echo $availability_id; ?>">

            <label for="first_name">ชื่อ</label>
            <input type="text" id="first_name" name="first_name" placeholder="ชื่อ" required>
            <span id="name_status"></span>

            <!-- เพิ่มฟิลด์ Last Name -->
            <label for="last_name">นามสกุล</label>
            <input type="text" id="last_name" name="last_name" placeholder="นามสกุล" required>
            <span id="last_name_status"></span>

            <label for="people">จำนวนคน</label>
            <input type="number" id="people" name="people" placeholder="จำนวนคน" min="1" max="10" required>

            <label for="time">เวลาในการจอง</label>
            <input type="text" id="time" name="time" value="<?php echo $time; ?>" readonly required>

            <div>
                <input type="checkbox" id="terms" name="terms" required>
                <label for="terms">อ่าน <a href="Booking_rules.php">กฎการจอง</a> แล้ว</label>
            </div>

            <button type="submit" id="submitBtn" disabled>จอง</button> <!-- ปุ่มจอง (เริ่มต้นถูกปิด) -->
        </form>
    </div>


    </div>

    <script src="scriptForm.js"></script> <!-- เรียกใช้ไฟล์ JavaScript แยกต่างหาก -->

</html>