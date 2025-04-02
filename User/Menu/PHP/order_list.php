<?php
$servername = "localhost";
$username = "root";
$password = "123456";
$dbname = "a_shabu";

$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ตรวจสอบว่ามีการส่งค่า reservation_id มาหรือไม่
if (!isset($_GET['reservation_id']) || empty($_GET['reservation_id'])) {
    echo "<script>alert('กรุณาระบุรหัสการจอง'); window.location.href='Homepage.php';</script>";
    exit();
}

$reservation_id = intval($_GET['reservation_id']); // รับค่า reservation_id

// ดึงข้อมูล menu_name (จากตาราง raw_material) และ quantity จากตาราง order
$sql = "SELECT rm.item_name, o.quantity
FROM `getting_table` g
JOIN `order` o ON g.getting_table_id = o.getting_table_id
JOIN `menu` m ON o.menu_id = m.menu_id
JOIN `raw_material` rm ON m.raw_material_id = rm.raw_material_id
WHERE g.reservation_id = ?"; // ใช้ ? เป็น placeholder สำหรับการป้องกัน SQL Injection
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $reservation_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>รายการสั่งอาหาร</title>
    <link rel="stylesheet" href="order_list.css">
</head>

<body>
    <div class="container">
        <h2>รายการสั่งอาหารสำหรับการจอง <?php echo htmlspecialchars($reservation_id); ?></h2>

        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ชื่อสินค้า</th>
                        <th>จำนวน</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['item_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>ไม่มีรายการสั่งอาหารสำหรับการจองนี้</p>
        <?php endif; ?>

        <!-- ปุ่มชำระเงิน -->
        <button onclick="window.location.href='payment.php?reservation_id=<?php echo $reservation_id; ?>'">ชำระเงิน</button>

        <!-- ปุ่มกลับไปหน้าที่ผ่านมา -->
        <button class="btn-back" onclick="window.history.back()">กลับ</button>
        </div>
</body>

</html>
