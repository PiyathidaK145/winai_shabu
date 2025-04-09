<?php
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
// รับค่า employee_id
$employee_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // รับค่าจากฟอร์ม
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $role_id = $_POST['role_id'];
    $created_at = $_POST['created_at'];

    // อัปเดตฐานข้อมูล
    $stmt = $conn->prepare("UPDATE employee SET first_name = ?, last_name = ?, email = ?, phone = ?, role_id = ?, created_at = ? WHERE employee_id = ?");
    $stmt->execute([$first_name, $last_name, $email, $phone, $role_id, $created_at, $employee_id]);


    echo "<script type='text/javascript'>
            window.location.href = 'employee.php';
          </script>";
    exit;
}

// ดึงข้อมูลพนักงาน
$stmt = $conn->prepare("SELECT * FROM employee WHERE employee_id = ?");
$stmt->execute([$employee_id]);
$employee = $stmt->fetch(PDO::FETCH_ASSOC);

// ดึงรายชื่อ role
$roles = $conn->query("SELECT * FROM role")->fetchAll(PDO::FETCH_ASSOC);
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
                <div class="container mt-5">

                    <h2 class="mb-4">แก้ไขข้อมูลพนักงาน</h2>
                    <form method="POST">
                        <div class="mb-3">
                            <label for="first_name" class="form-label">ชื่อ</label>
                            <input type="text" name="first_name" class="form-control" value="<?= htmlspecialchars($employee['first_name']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="last_name" class="form-label">นามสกุล</label>
                            <input type="text" name="last_name" class="form-control" value="<?= htmlspecialchars($employee['last_name']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">อีเมล</label>
                            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($employee['email']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">เบอร์โทร</label>
                            <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($employee['phone']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="role_id" class="form-label">หน้าที่</label>
                            <select name="role_id" class="form-select" required>
                                <?php foreach ($roles as $role): ?>
                                    <option value="<?= $role['role_id'] ?>" <?= $employee['role_id'] == $role['role_id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($role['role_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="created_at" class="form-label">วันและเวลาที่เข้าทำงาน</label>
                            <input type="datetime-local" name="created_at" class="form-control" value="<?= date('Y-m-d\TH:i', strtotime($employee['created_at'])) ?>" required>
                        </div>


                        <a href="employee.php" class="btn btn-secondary">กลับ</a>
                        <button type="submit" class="btn btn-primary">บันทึก</button>
                    </form>
</body>

</html>