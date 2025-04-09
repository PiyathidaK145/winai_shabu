<?php
date_default_timezone_set("Asia/Bangkok");
include '../../config/connect_db.php';

$id = $_GET['id'] ?? 0;

if ($id) {
    // ตรวจสอบว่า query สำเร็จหรือไม่
    $check = mysqli_query($conn, "SELECT * FROM raw_material WHERE supplier_id = '$id'");

    if ($check) {
        if (mysqli_num_rows($check) > 0) {
            // ลบ supplier_id ออกจาก raw_material ก่อน
            $update = mysqli_query($conn, "UPDATE raw_material SET supplier_id = NULL WHERE supplier_id = '$id'");

            if (!$update) {
                echo "<script>
                    alert('เกิดข้อผิดพลาดในการอัปเดต raw_material');
                    window.location.href = 'supplier.php';
                </script>";
                exit();
            }
        }

        // ลบ supplier ได้
        $delete = mysqli_query($conn, "DELETE FROM supplier WHERE supplier_id = '$id'");
        if ($delete) {
            header("Location: supplier.php");
            exit();
        } else {
            echo "เกิดข้อผิดพลาดในการลบซัพพลายเออร์: " . mysqli_error($conn);
        }

    } else {
        echo "ไม่สามารถตรวจสอบข้อมูล raw_material ได้: " . mysqli_error($conn);
    }
} else {
    echo "ไม่พบข้อมูลที่ต้องการลบ";
}

include dirname(__FILE__) . '/include/header.php';
?>
