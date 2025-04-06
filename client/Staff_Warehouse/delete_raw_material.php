<?php
date_default_timezone_set("Asia/Bangkok");
include '../../config/connect_db.php';

$id = $_GET['id'] ?? 0;

if ($id) {
    // ตรวจสอบว่ามีอยู่จริงไหม
    $raw = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM raw_material WHERE raw_material_id = '$id'"));
    
    if (!$raw) {
        echo "<script>alert('ไม่พบวัตถุดิบที่ต้องการลบ'); window.location='raw_material.php';</script>";
        exit();
    }

    // ลบรูปภาพที่เกี่ยวข้อง
    if (!empty($raw['image_url'])) {
        $image_path = "../../" . $raw['image_url'];
        if (file_exists($image_path)) {
            unlink($image_path);
        }
    }

    // ลบข้อมูลจาก menu ที่เกี่ยวข้อง
    mysqli_query($conn, "DELETE FROM menu WHERE raw_material_id = '$id'");

    // ลบจาก raw_material
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
