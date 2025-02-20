<?php
session_start();

// ดึงค่าชื่อ-นามสกุล ถ้ามีใน session
$first_name = isset($_SESSION['first_name']) ? $_SESSION['first_name'] : '';
$last_name = isset($_SESSION['last_name']) ? $_SESSION['last_name'] : '';

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
            <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($first_name); ?>" required readonly>

            <label for="last_name">นามสกุล</label>
            <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($last_name); ?>" required readonly>

            <label for="people">จำนวนคน</label>
            <input type="number" id="people" name="people" placeholder="จำนวนคน" min="1" max="10" required>

            <label for="time">เวลาในการจอง</label>
            <input type="text" id="time" name="time" value="<?php echo $time; ?>" readonly required>

            <div>
                <input type="checkbox" id="terms" name="terms" required>
                <label for="terms">อ่าน <a href="Booking_rules.php">กฎการจอง</a> แล้ว</label>
            </div>

            <button type="submit" id="submitBtn">จอง</button> 
        </form>
    </div>

    <script src="scriptForm.js"></script>
</body>
</html>