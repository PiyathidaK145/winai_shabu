<?php
$host = "localhost";
$user = "root";
$password = "123456";
$database = "winaishabu";

// เชื่อมต่อฐานข้อมูล
$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("การเชื่อมต่อล้มเหลว: " . $conn->connect_error);
}

// ตรวจสอบว่ามีการส่งค่า reservation_id หรือไม่
if (isset($_GET["reservation_id"])) {
    $reservation_id = $_GET["reservation_id"];

    // ตรวจสอบว่า reservation_id มีอยู่แล้วใน getting_table หรือไม่
    $check_sql = "SELECT COUNT(*) AS count FROM getting_table WHERE reservation_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $reservation_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    $check_row = $check_result->fetch_assoc();
    $check_stmt->close();
    
    if ($check_row['count'] > 0) {
        echo "<script>alert('หมายเลขนี้จองไปแล้ว กรุณาระบุเลขให้อีกครั้ง'); window.location.href='assign_table.php';</script>";
        exit();
    }

    // ตรวจสอบว่า reservation_id มีอยู่ในตาราง reservation หรือไม่
    $sql = "SELECT first_name, last_name, number_of_guest FROM reservation WHERE reservation_id = ? AND status = 'Comfirm'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $reservation_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $first_name = $row["first_name"];
        $last_name = $row["last_name"];
        $number_of_guest = $row["number_of_guest"];
    } else {
        echo "<script>alert('ไม่พบข้อมูลการจอง หรือสถานะไม่ถูกต้อง'); window.location.href='assign_table.php';</script>";
        exit();
    }
    $stmt->close();
}

// ดึงข้อมูลแพ็กเกจทั้งหมด
$package_sql = "SELECT package_id, package_name FROM package";
$package_result = $conn->query($package_sql);

// ดึงข้อมูลโปรโมชั่นทั้งหมด
$promotion_sql = "SELECT promotion_id, promotions_name FROM promotion";
$promotion_result = $conn->query($promotion_sql);

?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ข้อมูลการจอง</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">ข้อมูลการจอง</h2>
        <p><strong>รหัสการจอง:</strong> <?php echo isset($_GET["reservation_id"]) ? htmlspecialchars($_GET["reservation_id"]) : ''; ?></p>
        <p><strong>ชื่อ:</strong> <?php echo htmlspecialchars($first_name); ?></p>
        <p><strong>นามสกุล:</strong> <?php echo htmlspecialchars($last_name); ?></p>
        <p><strong>จำนวนคน:</strong> <?php echo htmlspecialchars($number_of_guest); ?> คน</p>

        <form action="process_selection.php" method="POST">
            <input type="hidden" name="reservation_id" value="<?php echo isset($_GET["reservation_id"]) ? htmlspecialchars($_GET["reservation_id"]) : ''; ?>">

            <div class="mb-3">
                <label for="package" class="form-label"><strong>เลือกแพ็กเกจ:</strong></label>
                <select class="form-control" name="package_id" required>
                    <option value="">-- กรุณาเลือกแพ็กเกจ --</option>
                    <?php while ($row = $package_result->fetch_assoc()) { ?>
                        <option value="<?php echo $row['package_id']; ?>"><?php echo htmlspecialchars($row['package_name']); ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="promotion" class="form-label"><strong>เลือกโปรโมชั่น:</strong></label>
                <select class="form-control" name="promotion_id">
                    <option value="">-- ไม่ใช้โปรโมชั่น --</option>
                    <?php while ($row = $promotion_result->fetch_assoc()) { ?>
                        <option value="<?php echo $row['promotion_id']; ?>"><?php echo htmlspecialchars($row['promotions_name']); ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="employee_id" class="form-label"><strong>รหัสพนักงาน:</strong></label>
                <input type="number" name="employee_id" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-success">ยืนยันการเลือก</button>
            <a href="assign_table.php" class="btn btn-secondary">ย้อนกลับ</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();
?>
