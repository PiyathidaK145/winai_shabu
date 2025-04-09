<?php
date_default_timezone_set("Asia/Bangkok");
include '../../config/connect_db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $package_id = $_POST['package_id'];
    $package_name = $_POST['package_name'];
    $discription = $_POST['discription'];
    $price = $_POST['price'];
    $menu_ids = isset($_POST['menu_ids']) ? $_POST['menu_ids'] : [];

    // 1. อัปเดตข้อมูล package
    $update_sql = "UPDATE package SET package_name = ?, discription = ?, price = ? WHERE package_id = ?";
    $stmt = mysqli_prepare($conn, $update_sql);
    mysqli_stmt_bind_param($stmt, "ssdi", $package_name, $discription, $price, $package_id);
    mysqli_stmt_execute($stmt);

    // 2. ลบเมนูเก่าทั้งหมดออกจาก package_item
    $delete_sql = "DELETE FROM package_item WHERE package_id = ?";
    $stmt_delete = mysqli_prepare($conn, $delete_sql);
    mysqli_stmt_bind_param($stmt_delete, "i", $package_id);
    mysqli_stmt_execute($stmt_delete);

    // 3. เพิ่มเมนูใหม่ที่เลือกกลับเข้าไป
    if (!empty($menu_ids)) {
        $insert_sql = "INSERT INTO package_item (package_id, menu_id) VALUES (?, ?)";
        $stmt_insert = mysqli_prepare($conn, $insert_sql);

        foreach ($menu_ids as $menu_id) {
            mysqli_stmt_bind_param($stmt_insert, "ii", $package_id, $menu_id);
            mysqli_stmt_execute($stmt_insert);
        }
    }

    // ปิด statement และ connection (optional)
    mysqli_stmt_close($stmt);
    mysqli_stmt_close($stmt_delete);
    if (isset($stmt_insert)) mysqli_stmt_close($stmt_insert);
    mysqli_close($conn);

    // Redirect
    header("Location: package.php?update=success");
    exit;
} else {
    // หากไม่ได้มากจาก POST ให้กลับ
    header("Location: package.php");
    exit;
}
?>
