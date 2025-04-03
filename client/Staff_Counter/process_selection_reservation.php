<?php
date_default_timezone_set("Asia/Bangkok");
include dirname(__FILE__) . '/../../config/connect_db.php';

// รับค่าจาก POST
$walkin_id = null;
$reservation_id = $_POST['reservation_id'] ?? null;
$employee_id = $_POST['employee_id'] ?? null;
$package_id = $_POST['package_id'] ?? null;
$promotion_id = $_POST['promotion_id'] ?? null;
$created_at = date("Y-m-d H:i:s");

// ดึงราคาจากตาราง package
$total_amount = 0;
if ($package_id) {
    $query_price = "SELECT price FROM package WHERE package_id = '$package_id'";
    $result_price = mysqli_query($conn, $query_price);

    if ($result_price && mysqli_num_rows($result_price) > 0) {
        $row_price = mysqli_fetch_assoc($result_price);
        $total_amount = $row_price['price'];
    }
}

// เตรียมคำสั่ง SQL สำหรับ insert
$sql = "
    INSERT INTO getting_table (
        walkin_id,
        reservation_id,
        employee_id,
        package_id,
        promotion_id,
        total_amount,
        created_at
    ) VALUES (
        ?, ?, ?, ?, ?, ?, ?
    )
";

// Prepare และ bind
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "iiiiiis", $walkin_id, $reservation_id, $employee_id, $package_id, $promotion_id, $total_amount, $created_at);

if (mysqli_stmt_execute($stmt)) {
    echo "<script>alert('บันทึกการรับโต๊ะสำเร็จ'); window.location.href = 'index.php';</script>";
} else {
    echo "<script>alert('เกิดข้อผิดพลาดในการบันทึก'); history.back();</script>";
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
