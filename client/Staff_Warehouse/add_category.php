<?php
date_default_timezone_set("Asia/Bangkok");

include '../../config/connect_db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_name = $_POST['category_name'];
    $description = $_POST['description'];

    $sql = "INSERT INTO category (category_name, description) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $category_name, $description);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: categories.php"); // กลับไปหน้ารายการหมวดหมู่
        exit();
    } else {
        echo "เกิดข้อผิดพลาด: " . mysqli_error($conn);
    }
}

include dirname(__FILE__) . '/include/header.php';
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>เพิ่มหมวดหมู่</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="row">
        <main class="main-wrapper col-md-9 ms-sm-auto py-4 col-lg-9 px-md-4 border-start">
            <div class="container mt-4">
                <div class="card shadow">
                    <div class="card-header bg-secondary text-white">
                        <h4 class="mb-0">เพิ่มหมวดหมู่เมนู</h4>
                    </div>
                    <div class="card-body">
                        <!-- Form เพิ่ม category -->
                        <form method="POST" action="">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label for="category_name" class="form-label">ชื่อหมวดหมู่</label>
                                    <input type="text" id="category_name" name="category_name" class="form-control" required>
                                </div>

                                <div class="col-md-12">
                                    <label for="description" class="form-label">คำอธิบาย</label>
                                    <textarea id="description" name="description" class="form-control" rows="3"></textarea>
                                </div>

                                <div class="col-md-12 text-end">
                                    <a href="categories.php" class="btn btn-danger">ยกเลิก</a>
                                    <button type="submit" class="btn btn-success">เพิ่มหมวดหมู่</button>
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