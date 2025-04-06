<?php
// edit_warehouse.php

date_default_timezone_set("Asia/Bangkok");
include '../../config/connect_db.php';

$id = $_GET['id'] ?? 0;

// ดึงข้อมูลเดิมมาแสดงในฟอร์ม
if ($id) {
    $query = mysqli_query($conn, "SELECT * FROM warehouse WHERE warehouse_id = '$id'");
    $warehouse = mysqli_fetch_assoc($query);

    if (!$warehouse) {
        echo "ไม่พบข้อมูลคลังวัตถุดิบ";
        exit();
    }
} else {
    echo "ไม่พบ ID ที่ต้องการแก้ไข";
    exit();
}

// เมื่อมีการกด Submit ฟอร์ม
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $locations = $_POST['locations'];

    $update_sql = "UPDATE warehouse 
                   SET name = '$name', discription = '$description', locations = '$locations' 
                   WHERE warehouse_id = '$id'";

    if (mysqli_query($conn, $update_sql)) {
        header("Location: warehouse.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

include dirname(__FILE__) . '/include/header.php';
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>แก้ไขคลังวัตถุดิบ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="row">
    <main class="main-wrapper col-md-9 ms-sm-auto py-4 col-lg-9 px-md-4 border-start">
        <div class="container mt-4">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0">แก้ไขคลังวัตถุดิบ</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">ชื่อคลังวัตถุดิบ</label>
                                <input type="text" id="name" name="name" class="form-control" required value="<?= htmlspecialchars($warehouse['name']) ?>">
                            </div>

                            <div class="col-md-6">
                                <label for="locations" class="form-label">จังหวัด / ที่ตั้ง</label>
                                <input type="text" id="locations" name="locations" class="form-control" required value="<?= htmlspecialchars($warehouse['locations']) ?>">
                            </div>

                            <div class="col-md-12">
                                <label for="description" class="form-label">คำอธิบาย</label>
                                <textarea id="description" name="description" class="form-control" rows="3"><?= htmlspecialchars($warehouse['discription']) ?></textarea>
                            </div>

                            <div class="col-md-12 text-end">
                                <a href="warehouse.php" class="btn btn-danger">ยกเลิก</a>
                                <button type="submit" class="btn btn-warning">บันทึกการเปลี่ยนแปลง</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>
<?php include dirname(__FILE__) . '/include/footer.php'; ?>
</body>
</html>
