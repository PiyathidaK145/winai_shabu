<?php
include '../../../config/connect_db.php';

if (!isset($_GET['getting_table_id'])) {
    echo "<script>alert('กรุณาระบุรหัส Getting Table'); window.location.href='../Home/Homepage.php';</script>";
    exit();
}

$getting_table_id = intval($_GET['getting_table_id']);

// ตรวจสอบว่าข้อมูล getting_table_id เป็นแบบ walk-in หรือ reservation
$sql_check = "SELECT reservation_id, walkin_id FROM getting_table WHERE getting_table_id = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("i", $getting_table_id);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows === 0) {
    echo "<script>alert('ไม่พบข้อมูล Getting Table นี้'); window.location.href='../Home/Homepage.php';</script>";
    exit();
}

$row_check = $result_check->fetch_assoc();
$reservation_id = $row_check['reservation_id'];
$walkin_id = $row_check['walkin_id'];

// หาก reservation_id หรือ walkin_id เป็น NULL ให้ดึงข้อมูลจาก table_availability
if (!is_null($reservation_id)) {
    // กรณีมี reservation_id
    $sql_table = "SELECT a.table_id 
                  FROM table_availability a
                  JOIN `table` t ON a.table_id = t.table_id
                  JOIN reservation r ON a.availability_id = r.availability_id
                  WHERE r.reservation_id = ?";
    $stmt_table = $conn->prepare($sql_table);
    $stmt_table->bind_param("i", $reservation_id);
} elseif (!is_null($walkin_id)) {
    // กรณีมี walkin_id
    $sql_table = "SELECT a.table_id 
                  FROM table_availability a
                  JOIN `table` t ON a.table_id = t.table_id
                  JOIN walkin w ON a.availability_id = w.availability_id
                  WHERE w.walkin_id = ?";
    $stmt_table = $conn->prepare($sql_table);
    $stmt_table->bind_param("i", $walkin_id);
}

$stmt_table->execute();
$result_table = $stmt_table->get_result();
$row_table = $result_table->fetch_assoc();
$table_id = $row_table['table_id'];
if (!is_null($reservation_id)) {
    $table_name = "การจอง โต๊ะ: " . $table_id; // ใช้ table_name จากการจอง
} else {
    $table_name = "Walk-in โต๊ะ: " . $table_id; // ใช้ table_name จาก walk-in
}


// ใช้ getting_table_id ดึงรายการเมนูที่สั่งจากตาราง order
$sql = "SELECT rm.item_name, o.quantity, o.status_waiter
        FROM `order` o
        JOIN `menu` m ON o.menu_id = m.menu_id
        JOIN `raw_material` rm ON m.raw_material_id = rm.raw_material_id
        WHERE o.getting_table_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $getting_table_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>รายการสั่งอาหาร</title>
    <link rel="stylesheet" href="../../CSS/order_list.css">
</head>
<body>
    <div class="container">
        <h2>รายการสั่งอาหาร (<?php echo htmlspecialchars($table_name); ?>)</h2>

        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ชื่อสินค้า</th>
                        <th>จำนวน</th>
                        <th>สถานะ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['item_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                            <td class="
                                <?php
                                    // กำหนด class สำหรับการแสดงผลสี
                                    if ($row['status_waiter'] === 'pending') {
                                        echo 'status-pending';
                                    } elseif ($row['status_waiter'] === 'served') {
                                        echo 'status-served';
                                    }
                                ?>">
                                <?php 
                                    // แปลสถานะ
                                    if ($row['status_waiter'] === 'pending') {
                                        echo 'กำลังเตรียม';
                                    } elseif ($row['status_waiter'] === 'served') {
                                        echo 'เสิร์ฟแล้ว';
                                    }
                                ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>ไม่มีรายการสั่งอาหารสำหรับโต๊ะนี้</p>
        <?php endif; ?>
        <!-- ปุ่มกลับไปหน้าที่ผ่านมา -->
        <button class="btn-back" onclick="window.history.back()">กลับ</button>

        <!-- ปุ่มชำระเงิน -->
        <button onclick="window.location.href='../Payment/payment.php?getting_table_id=<?php echo $getting_table_id; ?>'">ชำระเงิน</button>

    </div>
</body>
</html>
