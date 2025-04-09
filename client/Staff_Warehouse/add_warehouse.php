<?php
date_default_timezone_set("Asia/Bangkok");
include '../../config/connect_db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $locations = $_POST['locations'];

    $sql = "INSERT INTO warehouse (name, discription, locations)
            VALUES ('$name', '$description', '$locations')";

    if (mysqli_query($conn, $sql)) {
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
    <title>เพิ่มคลังวัตถุดิบ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<div class="row">
    <main class="main-wrapper col-md-9 ms-sm-auto py-4 col-lg-9 px-md-4 border-start">
        <div class="container mt-4">
            <div class="card shadow">
                <div class="card-header bg-secondary text-white">
                    <h4 class="mb-0">เพิ่มคลังวัตถุดิบ</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">ชื่อคลังวัตถุดิบ</label>
                                <input type="text" id="name" name="name" class="form-control" required>
                            </div>

                            <div class="col-md-6">
                                <label for="locations" class="form-label">จังหวัด / ที่ตั้ง</label>
                                <input type="text" id="locations" name="locations" class="form-control" required>
                            </div>

                            <div class="col-md-12">
                                <label for="description" class="form-label">คำอธิบาย</label>
                                <textarea id="description" name="description" class="form-control" rows="3"></textarea>
                            </div>

                            <div class="col-md-12 text-end">
                                <a href="warehouse.php" class="btn btn-danger">ยกเลิก</a>
                                <button type="submit" class="btn btn-success">เพิ่มคลังวัตถุดิบ</button>
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
