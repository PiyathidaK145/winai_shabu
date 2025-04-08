<?php
date_default_timezone_set("Asia/Bangkok");
include '../../config/connect_db.php';

$id = $_GET['id'] ?? 0;

if ($id) {
    // ตรวจสอบว่ามีวัตถุดิบอยู่จริงหรือไม่
    $raw = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM raw_material WHERE raw_material_id = '$id'"));

    if (!$raw) {
        echo "<script>alert('ไม่พบวัตถุดิบที่ต้องการลบ'); window.location='raw_material.php';</script>";
        exit();
    }

    // ลบรูปภาพถ้ามี
    if (!empty($raw['image_url'])) {
        $image_path = "../../" . $raw['image_url'];
        if (file_exists($image_path)) {
            unlink($image_path);
        }
    }

    // ดึง menu_id ทั้งหมดที่อิง raw_material_id นี้
    $result_menu = mysqli_query($conn, "SELECT menu_id FROM menu WHERE raw_material_id = '$id'");
    $menu_ids = [];
    while ($row = mysqli_fetch_assoc($result_menu)) {
        $menu_ids[] = $row['menu_id'];
    }

    // ดึง import_raw_material_id ทั้งหมดจาก menu_id เหล่านี้
    $import_ids = [];
    foreach ($menu_ids as $menu_id) {
        $res_import = mysqli_query($conn, "SELECT import_raw_material_id FROM import_raw_material WHERE menu_id = '$menu_id'");
        while ($row = mysqli_fetch_assoc($res_import)) {
            $import_ids[] = $row['import_raw_material_id'];
        }
    }

    // ลบจาก calculate_raw_material
    foreach ($import_ids as $import_id) {
        mysqli_query($conn, "DELETE FROM calculate_raw_material WHERE import_raw_material_id = '$import_id'");
    }

    // ลบจาก import_raw_material
    foreach ($menu_ids as $menu_id) {
        mysqli_query($conn, "DELETE FROM import_raw_material WHERE menu_id = '$menu_id'");
    }

    // ลบจาก menu
    mysqli_query($conn, "DELETE FROM menu WHERE raw_material_id = '$id'");

    // ลบ raw_material
    $delete_raw = mysqli_query($conn, "DELETE FROM raw_material WHERE raw_material_id = '$id'");

    if ($delete_raw) {
        echo "<script>alert('ลบวัตถุดิบเรียบร้อย'); window.location='raw_material.php';</script>";
        exit();
    } else {
        echo "เกิดข้อผิดพลาด: " . mysqli_error($conn);
    }
} else {
    echo "<script>alert('ไม่พบข้อมูลที่ต้องการลบ'); window.location='raw_material.php';</script>";
}
?>
