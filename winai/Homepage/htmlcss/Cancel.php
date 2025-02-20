<?php
$servername = "localhost";
$username = "root";
$password = "123456";
$dbname = "winaishabu";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$availability_id = isset($_GET['availability_id']) ? intval($_GET['availability_id']) : 0;
$first_name = '';
$last_name = '';
$is_cancelled = false;  // ตัวแปรตรวจสอบว่าเป็นการจองที่ยกเลิกแล้วหรือไม่

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == "cancel") {
    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];

    if ($availability_id > 0) {
        // ตรวจสอบว่ามีการจองอยู่จริง และยังไม่ได้ถูกยกเลิก
        $sql = "SELECT reservation_id FROM reservation WHERE availability_id = ? AND first_name = ? AND last_name = ? AND status != 'Cancel'";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iss", $availability_id, $first_name, $last_name);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $reservation_id = $row['reservation_id'];

            // ✅ อัปเดตสถานะของการจองเป็น 'Cancel'
            $update_reservation = $conn->prepare("UPDATE reservation SET status = 'Cancel' WHERE reservation_id = ?");
            $update_reservation->bind_param("i", $reservation_id);
            $update_reservation->execute();
            $update_reservation->close();

            // ✅ อัปเดต table_availability เป็น 'Free'
            $update_availability = $conn->prepare("UPDATE table_availability SET status = 'Free' WHERE availability_id = ?");
            $update_availability->bind_param("i", $availability_id);
            $update_availability->execute();
            $update_availability->close();

            $is_cancelled = true;  // บันทึกว่าได้ทำการยกเลิกแล้ว

            // แจ้งเตือนและรีไดเรกต์กลับไปที่หน้า Homepage.php
            echo "<script>
                    alert('การจองถูกยกเลิกเรียบร้อย');
                    window.location.href = 'Homepage.php?table={$_GET['table']}&cancel=true';
                  </script>";
        } else {
            echo "<script>alert('ไม่พบการจองนี้ในระบบ หรือถูกยกเลิกไปแล้ว');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('ข้อมูลไม่ถูกต้อง!');</script>";
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
<div class="success-box">
    <button class="close-btn" onclick="closeBox()">&times;</button>
    <h2>ยกเลิกการจอง</h2>
    <form method="POST">
        <input type="hidden" name="availability_id" value="<?php echo $availability_id; ?>">
        <label>ชื่อ:</label>
        <input type="text" name="first_name" required>
        <label>นามสกุล:</label>
        <input type="text" name="last_name" required>

        <?php if ($is_cancelled): ?>
            <button type="button" onclick="window.location.href='Homepage.php'">กลับหน้าแรก</button>
        <?php else: ?>
            <button type="submit" name="action" value="cancel">ยกเลิกการจอง</button>
        <?php endif; ?>
    </form>
</div>
<script>
    function closeBox() {
        window.location.href = "Homepage.php"; 
    }
</script>

</body>
</html>
