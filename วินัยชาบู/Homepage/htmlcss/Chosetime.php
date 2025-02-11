<?php
$servername = "localhost";
$username = "root";
$password = "123456";
$dbname = "winaishabu";

// เชื่อมต่อกับฐานข้อมูล
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$tableNumber = isset($_GET['table']) ? (int) $_GET['table'] : 0;

if ($tableNumber < 1 || $tableNumber > 20) {
    echo "<h1 style='text-align: center; color: red;'>หมายเลขโต๊ะไม่ถูกต้อง</h1>";
    exit();
}

// ดึงข้อมูลเวลาจากตาราง `time_reserversion`
$sql = "SELECT * FROM time_reserversion";
$result = $conn->query($sql);

$times = [];  // กำหนดค่าเริ่มต้นให้กับตัวแปร $times

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $times[] = $row['time'];  // เพิ่มเวลาแต่ละอันเข้าในอาเรย์ $times
    }
}

if (empty($times)) {
    echo "<h1 style='text-align: center; color: red;'>ไม่พบข้อมูลเวลาในระบบ</h1>";
    exit();
}

// เมื่อผู้ใช้กดจอง
if (isset($_GET['time'])) {
    $time = $_GET['time'];
    $availability_id = ($tableNumber * 100) + (array_search($time, $times) * 2) + 16;

    // ตรวจสอบว่า availability_id นี้มีอยู่ในระบบหรือไม่
    $stmt = $conn->prepare("SELECT COUNT(*) FROM table_availability WHERE availability_id = ?");
    $stmt->bind_param("i", $availability_id);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count == 0) {
        echo "<script>alert('ไม่พบ availability_id นี้ในระบบ'); window.history.back();</script>";
        exit();
    }

    // เพิ่มข้อมูลลงใน table_availability และตั้งสถานะเป็น 'Free'
    $stmt = $conn->prepare("INSERT INTO table_availability (availability_id, table_id, time_id, status) VALUES (?, ?, ?, 'Free')");
    $stmt->bind_param("iis", $availability_id, $tableNumber, $time);
    if ($stmt->execute()) {
        echo "<script>alert('จองสำเร็จ'); window.location.href = 'Successfully.php?availability_id=$availability_id&table=$tableNumber&time=$time';</script>";
    } else {
        echo "<script>alert('เกิดข้อผิดพลาดในการจอง'); window.history.back();</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เลือกเวลาจองโต๊ะ</title>
    <link rel="stylesheet" href="stylesChose.css">
</head>
<body>
    <div class="header">
        <?php echo "Winai's Shabu"; ?>
    </div>

    <h1 style="text-align: center;" id="table-number">
        <?php echo "โต๊ะหมายเลข " . $tableNumber; ?>
    </h1>

    <table>
        <thead>
            <tr>
                <?php
                // แสดงเวลาที่ดึงมาจากฐานข้อมูล
                foreach ($times as $time) {
                    echo "<th>$time</th>";
                }
                ?>
            </tr>
        </thead>
        <tbody>
            <tr>
                <?php
                foreach ($times as $index => $time) {
                    $availability_id = ($tableNumber * 100) + ($index * 2) + 16;

                    // ค้นหาสถานะของ availability_id นี้
                    $sql = "SELECT status FROM reservation WHERE availability_id = ? LIMIT 1";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $availability_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $status = null;

                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $status = $row['status'];
                    }

                    // แสดงปุ่มให้เปลี่ยนตามสถานะที่ตรวจพบ
                    if ($status === 'Comfirm') {
                        echo "<td class='reserved'>
                                <a href='Cancel.php?availability_id=$availability_id&table=$tableNumber&time=$time' style='color: red; text-decoration: none;'>
                                    ยกเลิก
                                </a>
                              </td>";
                    } else {
                        echo "<td class='available'>
                                <a href='Form.php?availability_id=$availability_id&table=$tableNumber&time=$time' style='color: green; text-decoration: none;'>
                                    จอง
                                </a>
                              </td>";
                    }
                }
                ?>
            </tr>
        </tbody>
    </table>

    <?php $conn->close(); ?>
</body>
</html>
