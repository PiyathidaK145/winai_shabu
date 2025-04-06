<?php
date_default_timezone_set("Asia/Bangkok");
include '../../config/connect_db.php';

$id = $_GET['id'] ?? 0;

if ($id) {
    // 1. ตรวจสอบว่า warehouse มี raw_material ที่ใช้มันอยู่หรือไม่
    $check = mysqli_query($conn, "SELECT * FROM raw_material WHERE warehouse_id = '$id'");

    if (mysqli_num_rows($check) > 0) {
        // 2. ตั้งค่า warehouse_id ให้ NULL แทนการลบ
        mysqli_query($conn, "UPDATE raw_material SET warehouse_id = NULL WHERE warehouse_id = '$id'");
    }

    // 3. ลบ warehouse ได้อย่างปลอดภัย
    $delete = "DELETE FROM warehouse WHERE warehouse_id = '$id'";
    if (mysqli_query($conn, $delete)) {
        header("Location: warehouse.php");
        exit();
    } else {
        echo "เกิดข้อผิดพลาดในการลบคลังวัตถุดิบ: " . mysqli_error($conn);
    }
} else {
    echo "ไม่พบข้อมูลที่ต้องการลบ";
}
?>
