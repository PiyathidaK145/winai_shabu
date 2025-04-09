<?php
date_default_timezone_set("Asia/Bangkok");
include '../../config/connect_db.php';

$package_id = $_GET['id'] ?? null;

if (!$package_id) {
    header("Location: package.php?error=nopackageid");
    exit;
}

// 1. ลบเมนูในแพ็คเกจ (package_item)
$delete_items_sql = "DELETE FROM package_item WHERE package_id = ?";
$stmt_items = mysqli_prepare($conn, $delete_items_sql);
mysqli_stmt_bind_param($stmt_items, "i", $package_id);
mysqli_stmt_execute($stmt_items);

// 2. ลบแพ็คเกจ (package)
$delete_package_sql = "DELETE FROM package WHERE package_id = ?";
$stmt_package = mysqli_prepare($conn, $delete_package_sql);
mysqli_stmt_bind_param($stmt_package, "i", $package_id);
mysqli_stmt_execute($stmt_package);

// ปิด statement และ connection
mysqli_stmt_close($stmt_items);
mysqli_stmt_close($stmt_package);
mysqli_close($conn);

// 3. Redirect กลับหน้า package
header("Location: package.php?delete=success");
exit;
?>
