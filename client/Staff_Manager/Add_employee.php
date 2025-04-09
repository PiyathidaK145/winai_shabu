<?php
// เริ่มต้นเซสชันเพื่อเก็บข้อมูล
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_GET['ajax'])) {
    include 'include/header.php';
}

include dirname(__FILE__) . '/../../config/connect_db.php';

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// ดึงข้อมูลตำแหน่งพนักงานจากตาราง role
$positions = [];
try {
    $stmt = $conn->prepare("SELECT * FROM role");  // ดึงข้อมูลตำแหน่งจากตาราง role
    $stmt->execute();
    $positions = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// ตรวจสอบว่ามีการส่งข้อมูลจากฟอร์มหรือไม่
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // รับข้อมูลจากฟอร์ม
    $first_name = htmlspecialchars($_POST['first_name']);
    $last_name = htmlspecialchars($_POST['last_name']);
    $email = htmlspecialchars($_POST['email']);
    $user = htmlspecialchars($_POST['user']);
    $password = htmlspecialchars($_POST['password']);
    $phone = htmlspecialchars($_POST['phone']);
    $role_id = htmlspecialchars($_POST['role']); // รับค่า role_id จากฟอร์ม

    // เพิ่มข้อมูลพนักงานลงในฐานข้อมูล employee
    try {
        $stmt = $conn->prepare("INSERT INTO employee (first_name, last_name, email, phone, role_id) VALUES (:first_name, :last_name, :email, :phone, :role_id)");
        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':role_id', $role_id);
        $stmt->execute();

        // แสดงป๊อบอัพแจ้งเตือนสำเร็จ
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
        echo "<script>
                Swal.fire({
                    title: 'สำเร็จ!',
                    text: 'บันทึกข้อมูลพนักงานสำเร็จแล้ว!',
                    icon: 'success',
                    confirmButtonText: 'ตกลง'
                }).then(function() {
                    window.location = 'staff.php';
                });
              </script>";
    } catch (PDOException $e) {
        echo "<div class='alert alert-danger' role='alert'>Error: " . $e->getMessage() . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <main class="main-wrapper col-md-9 ms-sm-auto py-4 col-lg-9 px-md-4 border-start">
            <div class="container">
                <h1 class="h2 mb-0">เพิ่มข้อมูลพนักงาน</h1>
                <div class="form-container">
                    <form method="POST">
                        <div class="mb-3">
                            <label for="first_name" class="form-label">ชื่อ</label>
                            <input type="text" name="first_name" class="form-control" id="first_name" placeholder="Enter first name" required>
                        </div>
                        <div class="mb-3">
                            <label for="last_name" class="form-label">นามสกุล</label>
                            <input type="text" name="last_name" class="form-control" id="last_name" placeholder="Enter last name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">อีเมล</label>
                            <input type="email" name="email" class="form-control" id="email" placeholder="Enter email" required>
                        </div>
                        <div class="mb-3">
                            <label for="user" class="form-label">Username</label>
                            <input type="text" name="user" class="form-control" id="user" placeholder="Enter username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" id="password" placeholder="Enter password" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">เบอร์โทร</label>
                            <input type="tel" name="phone" class="form-control" id="phone" placeholder="Enter phone number" required>
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">ตำแหน่ง</label>
                            <select name="role" class="form-select" id="role" required>
                                <option value="">Select Position</option>
                                <?php
                                // แสดงตำแหน่งจากฐานข้อมูล
                                foreach ($positions as $role) {
                                    echo "<option value='" . htmlspecialchars($role['role_id']) . "'>" . htmlspecialchars($role['role_name']) . "</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <!-- แบ่งปุ่มออกเป็น 2 ปุ่มที่อยู่ในแถวเดียวกัน -->
                        <div class="form-buttons">
                            <a href="employee.php" class="btn btn-secondary">กลับ</a>
                            <button type="submit" class="btn btn-primary">บันทึกข้อมูล</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>