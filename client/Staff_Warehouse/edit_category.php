<?php
ob_start();
date_default_timezone_set("Asia/Bangkok");
include '../../config/connect_db.php';

$id = $_GET['id'] ?? 0;

// เมื่อมีการ submit ฟอร์ม
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_name = $_POST['category_name'];
    $description = $_POST['description'];

    $sql = "UPDATE category SET category_name = ?, description = ? WHERE category_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssi", $category_name, $description, $id);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: categories.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// ดึงข้อมูล category เดิม
$result = mysqli_query($conn, "SELECT * FROM category WHERE category_id = '$id'");
$data = mysqli_fetch_assoc($result);

include dirname(__FILE__) . '/include/header.php';
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>แก้ไขหมวดหมู่</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="row">
        <main class="main-wrapper col-md-9 ms-sm-auto py-4 col-lg-9 px-md-4 border-start">
            <div class="container mt-4">
                <div class="card shadow">
                    <div class="card-header bg-secondary text-white">
                        <h4 class="mb-0">แก้ไขหมวดหมู่เมนู</h4>
                    </div>
                    <div class="card-body">
                        <!-- Form แก้ไข category -->
                        <form method="POST" action="">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label for="category_name" class="form-label">ชื่อหมวดหมู่</label>
                                    <input type="text" id="category_name" name="category_name" class="form-control" value="<?= htmlspecialchars($data['category_name']) ?>" required>
                                </div>

                                <div class="col-md-12">
                                    <label for="description" class="form-label">คำอธิบาย</label>
                                    <textarea id="description" name="description" class="form-control" rows="3"><?= htmlspecialchars($data['description']) ?></textarea>
                                </div>

                                <div class="col-md-12 text-end">
                                    <a href="categories.php" class="btn btn-secondary">ย้อนกลับ</a>
                                    <button type="submit" class="btn btn-warning">อัปเดตหมวดหมู่</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>

</html>