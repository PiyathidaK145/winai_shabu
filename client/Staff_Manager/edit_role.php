<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include dirname(__FILE__) . '/../../config/connect_db.php';
include dirname(__FILE__) . '/include/header.php';

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Fetch role data for the selected role ID
if (isset($_GET['role_id'])) {
    $stmt = $conn->prepare("SELECT * FROM role WHERE role_id = :role_id");
    $stmt->execute(['role_id' => $_GET['role_id']]);
    $role = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Handle update operation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateRole'])) {
    $stmt = $conn->prepare("UPDATE role SET role_name = :role_name, description = :description, permissions = :permissions WHERE role_id = :role_id");
    $stmt->execute([
        'role_name' => $_POST['role_name'],
        'description' => $_POST['description'],
        'permissions' => $_POST['permissions'],
        'role_id' => $_POST['role_id']
    ]);
    echo "<script type='text/javascript'>
            window.location.href = 'Role_employee.php';
          </script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขหน้าที่พนักงาน</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <main class="main-wrapper col-md-9 ms-sm-auto py-4 col-lg-9 px-md-4 border-start">
                <div class="container mt-5">
                    <h2 class="text-center">แก้ไขหน้าที่พนักงาน</h2>
                    <form method="POST">
                        <input type="hidden" name="role_id" value="<?= $role['role_id'] ?>">
                        <div class="form-group mb-3">
                            <label for="role_name">ชื่อหน้าที่</label>
                            <input type="text" id="role_name" name="role_name" class="form-control" value="<?= htmlspecialchars($role['role_name']) ?>" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="description">คำอธิบาย</label>
                            <textarea id="description" name="description" class="form-control" rows="4" required><?= htmlspecialchars($role['description']) ?></textarea>
                        </div>
                        <div class="form-group mb-3">
                            <label for="permissions">สิทธิ์</label>
                            <textarea id="permissions" name="permissions" class="form-control" rows="4" required><?= htmlspecialchars($role['permissions']) ?></textarea>
                        </div>
                        <div class="form-group">
                            <a href="Role_employee.php" class="btn btn-secondary">กลับ</a>
                            <button type="submit" name="updateRole" class="btn btn-primary">บันทึก</button>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>
</body>

</html>