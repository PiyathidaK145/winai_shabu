<?php
date_default_timezone_set("Asia/Bangkok");
include '../../config/connect_db.php';

$id = $_GET['id'] ?? 0;

if ($id) {
    $check_raw = mysqli_query($conn, "SELECT * FROM raw_material WHERE category_id = '$id' LIMIT 1");
    $check_supplier = mysqli_query($conn, "SELECT * FROM supplier WHERE category_id = '$id' LIMIT 1");

    if (mysqli_num_rows($check_raw) > 0 || mysqli_num_rows($check_supplier) > 0) {
        // อัปเดตให้ category_id เป็น NULL แทนการลบทันที (เพื่อไม่ให้ error)
        mysqli_query($conn, "UPDATE raw_material SET category_id = NULL WHERE category_id = '$id'");
        mysqli_query($conn, "UPDATE supplier SET category_id = NULL WHERE category_id = '$id'");
    }

    // 3. ลบ category ได้อย่างปลอดภัย
    $sql = "DELETE FROM category WHERE category_id = '$id'";
    if (mysqli_query($conn, $sql)) {
        header("Location: categories.php");
        exit();
    } else {
        echo "เกิดข้อผิดพลาดในการลบหมวดหมู่: " . mysqli_error($conn);
    }
} else {
    echo "ไม่พบข้อมูลที่ต้องการลบ";
}

include dirname(__FILE__) . '/include/header.php';
?>
