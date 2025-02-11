<?php
$servername = "localhost";
$username = "root";
$password = "123456";
$dbname = "winaishabu";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$availability_id = $_GET['availability_id'];
$first_name = '';
$last_name = '';
$is_cancelled = false;  // ตัวแปรตรวจสอบว่าเป็นการจองที่ยกเลิกแล้วหรือไม่

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];

    // ตรวจสอบว่ามีการจองอยู่จริง และไม่ได้ถูกยกเลิกไปแล้ว
    $sql = "SELECT * FROM reservation WHERE availability_id = ? AND first_name = ? AND last_name = ? AND status != 'cancel'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $availability_id, $first_name, $last_name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // อัปเดตสถานะเป็น 'cancel'
        $update_sql = "UPDATE reservation SET status = 'cancel' WHERE availability_id = ? AND first_name = ? AND last_name = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("iss", $availability_id, $first_name, $last_name);

        if ($update_stmt->execute()) {
            $is_cancelled = true;  // ปรับสถานะเมื่อยกเลิกการจองสำเร็จ
            // รีไดเร็กต์กลับไปที่หน้า Homepage.php พร้อมกับพารามิเตอร์ cancel=true
            echo "<script>
                    alert('การจองถูกยกเลิกเรียบร้อย');
                    window.location.href = 'Homepage.php?table={$_GET['table']}&cancel=true';
                  </script>";
        } else {
            echo "<script>alert('เกิดข้อผิดพลาดในการยกเลิก กรุณาลองใหม่');</script>";
        }
    } else {
        echo "<script>alert('ไม่พบการจองนี้ในระบบ');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ยกเลิกการจอง</title>
    <link rel="stylesheet" type="text/css" href="stylesCancel.css">
</head>
<body>
<div class="container">
    <h2>ยกเลิกการจอง</h2>
    <form method="POST">
        <input type="hidden" name="availability_id" value="<?php echo $_GET['availability_id']; ?>">
        <label>ชื่อ:</label>
        <input type="text" name="first_name" required>
        <label>นามสกุล:</label>
        <input type="text" name="last_name" required>
        
        <?php if ($is_cancelled): ?>
            <!-- แสดงปุ่ม "จองใหม่" เมื่อการจองถูกยกเลิกแล้ว -->
            <button type="submit" name="action" value="book">จองใหม่</button>
        <?php else: ?>
            <!-- แสดงปุ่ม "ยกเลิกการจอง" เมื่อการจองยังไม่ถูกยกเลิก -->
            <button type="submit" name="action" value="cancel">ยกเลิกการจอง</button>
        <?php endif; ?>
    </form>
</div>

<?php
// ถ้ามีการยกเลิกการจองเสร็จสิ้นและถูกส่งผ่านพารามิเตอร์ cancel=true ให้แสดงข้อความยืนยัน
if (isset($_GET['cancel']) && $_GET['cancel'] == 'true') {
    echo "<script>alert('การจองถูกยกเลิกเรียบร้อย');</script>";
}
?>

</body>
</html>
