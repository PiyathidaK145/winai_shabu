<?php
date_default_timezone_set("Asia/Bangkok");
include '../../config/connect_db.php';

// ตรวจสอบว่ามาจาก form POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // รับข้อมูลจากฟอร์ม
    $package_name = $_POST['package_name'];
    $discription = $_POST['discription'];
    $price = $_POST['price'];
    $menu_ids = isset($_POST['menu_ids']) ? $_POST['menu_ids'] : [];

    // 1. เพิ่มแพ็คเกจใหม่
    $insert_package_sql = "INSERT INTO package (package_name, discription, price) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $insert_package_sql);
    mysqli_stmt_bind_param($stmt, "ssd", $package_name, $discription, $price);
    mysqli_stmt_execute($stmt);

    // ดึง package_id ล่าสุดที่เพิ่ม
    $package_id = mysqli_insert_id($conn);

    // 2. เพิ่มเมนูทั้งหมดที่เลือกลง package_item
    $insert_item_sql = "INSERT INTO package_item (package_id, menu_id) VALUES (?, ?)";
    $stmt_item = mysqli_prepare($conn, $insert_item_sql);

    foreach ($menu_ids as $menu_id) {
        mysqli_stmt_bind_param($stmt_item, "ii", $package_id, $menu_id);
        mysqli_stmt_execute($stmt_item);
    }

    // 3. Redirect ไปยังหน้า package พร้อมข้อความ
    header("Location: package.php?success=1");
    exit();
} else {
    // ถ้าเข้ามาตรงๆ ไม่ใช่ผ่าน form
    header("Location: package.php");
    exit();
}
?>
