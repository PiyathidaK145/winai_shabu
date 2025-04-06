<?php
ob_start();
date_default_timezone_set("Asia/Bangkok");
include '../../config/connect_db.php';

$id = $_GET['id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $location = $_POST['location'];
    $website = $_POST['website'];
    $category_id = $_POST['category_id'];

    $sql = "UPDATE supplier SET name='$name', phone='$phone', email='$email', location='$location', website='$website', category_id='$category_id'
            WHERE supplier_id='$id'";

    if (mysqli_query($conn, $sql)) {
        header("Location: supplier.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// ดึงข้อมูลเดิมมาแสดงในฟอร์ม
$result = mysqli_query($conn, "SELECT * FROM supplier WHERE supplier_id='$id'");
$data = mysqli_fetch_assoc($result);

include dirname(__FILE__) . '/include/header.php';
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>แก้ไขซัพพลายเออร์</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="row">
        <main class="main-wrapper col-md-9 ms-sm-auto py-4 col-lg-9 px-md-4 border-start">
            <div class="container mt-4">
                <div class="card shadow">
                    <div class="card-header bg-secondary text-white">
                        <h4 class="mb-0">แก้ไขซัพพลายเออร์</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">ชื่อซัพพลายเออร์</label>
                                    <input type="text" id="name" name="name" class="form-control" value="<?= $data['name'] ?>" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="phone" class="form-label">เบอร์โทร</label>
                                    <input type="text" id="phone" name="phone" class="form-control" value="<?= $data['phone'] ?>">
                                </div>

                                <div class="col-md-6">
                                    <label for="email" class="form-label">อีเมล</label>
                                    <input type="email" id="email" name="email" class="form-control" value="<?= $data['email'] ?>">
                                </div>

                                <div class="col-md-6">
                                    <label for="website" class="form-label">เว็บไซต์</label>
                                    <input type="text" id="website" name="website" class="form-control" value="<?= $data['website'] ?>">
                                </div>

                                <div class="col-md-12">
                                    <label for="location" class="form-label">ที่อยู่</label>
                                    <textarea id="location" name="location" class="form-control" rows="2"><?= $data['location'] ?></textarea>
                                </div>

                                <div class="col-md-6">
                                    <label for="category_id" class="form-label">หมวดหมู่</label>
                                    <select name="category_id" id="category_id" class="form-select" required>
                                        <?php
                                        $categories = mysqli_query($conn, "SELECT * FROM category");
                                        while ($cat = mysqli_fetch_assoc($categories)) {
                                            $selected = $cat['category_id'] == $data['category_id'] ? 'selected' : '';
                                            echo "<option value='{$cat['category_id']}' $selected>{$cat['category_name']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="col-md-12 text-end">
                                    <a href="supplier.php" class="btn btn-secondary">ย้อนกลับ</a>
                                    <button type="submit" class="btn btn-warning">อัปเดตข้อมูล</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>