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
// Handle add operation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addRole'])) {
    // Check if the role already exists
    $stmt = $conn->prepare("SELECT COUNT(*) FROM role WHERE role_name = :role_name");
    $stmt->execute(['role_name' => $_POST['role_name']]);
    $roleExists = $stmt->fetchColumn();

    if ($roleExists) {
        echo "<script>alert('บทบาทนี้มีอยู่แล้ว');</script>";
    } else {
        // Insert the new role
        $stmt = $conn->prepare("INSERT INTO role (role_name, description, permissions) VALUES (:role_name, :description, :permissions)");
        $stmt->execute([
            'role_name' => $_POST['role_name'],
            'description' => $_POST['description'],
            'permissions' => $_POST['permissions']
        ]);
    }
}
// Fetch roles for display
$stmt = $conn->prepare("SELECT * FROM role");
$stmt->execute();
$roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }

        .container {
            max-width: 1200px;
            margin-top: 50px;
        }

        table {
            width: 100%;
            margin-top: 30px;
            border-collapse: collapse;
        }

        table th,
        table td {
            padding: 15px;
            text-align: center;
            border: 1px solid #ddd;
        }

        table th {
            background-color: #007bff;
            color: white;
        }

        .btn-action {
            margin: 0 5px;
            border-radius: 5px;
        }

        .btn-add {
            background-color: #007bff;
            color: #fff;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-control {
            border-radius: 5px;
            padding: 0.75rem;
        }

        .btn-primary {
            background-color: #007bff;
            border-radius: 5px;
            padding: 0.75rem 1.25rem;
        }

        .edit-btn,
        .delete-btn {
            cursor: pointer;
        }

        .modal-content {
            border-radius: 10px;
        }

        .edit-role-form {
            margin-top: 30px;
            background: #ffffff;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.edit-btn').forEach(button => {
                button.addEventListener('click', function () {
                    const roleId = this.dataset.roleId;
                    window.location.href = `edit_role.php?role_id=${roleId}`;
                });
            });

            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function () {
                    const roleId = this.dataset.roleId;
                    if (confirm('คุณต้องการลบบทบาทนี้หรือไม่?')) {
                        window.location.href = `delete_role.php?role_id=${roleId}`;
                    }
                });
            });
        });
    </script>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <main class="main-wrapper col-md-9 ms-sm-auto py-4 col-lg-9 px-md-4 border-start">
                <div class="container mt-5">
                    <h1 class="text-center mb-5">จัดการหน้าที่พนักงาน</h1>

                    <!-- Add Role Form -->
                    <div class="edit-role-form">
                        <h2 class="text-center">เพิ่มหน้าที่พนักงาน</h2>
                        <form method="POST">
                            <div class="form-group">
                                <input type="text" name="role_name" class="form-control" placeholder="ชื่อบทบาท" required>
                            </div>
                            <div class="form-group">
                                <textarea name="description" class="form-control" placeholder="คำอธิบาย" required></textarea>
                            </div>
                            <div class="form-group">
                                <textarea name="permissions" class="form-control" placeholder="สิทธิ์การใช้งาน" required></textarea>
                            </div>
                            <div class="form-group">
                                <button type="submit" name="addRole" class="btn btn-primary">ตกลง</button>
                            </div>
                        </form>
                    </div>

                    <!-- Existing Roles Table -->
                    <h2 class="text-center mt-5">หน้าที่พนักงาน</h2>
                    <table class="table-responsive">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>ชื่อบทบาท</th>
                                <th>คำอธิบาย</th>
                                <th>สิทธิ์</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($roles as $role): ?>
                                <tr>
                                    <td><?= htmlspecialchars($role['role_id']) ?></td>
                                    <td><?= htmlspecialchars($role['role_name']) ?></td>
                                    <td><?= htmlspecialchars($role['description']) ?></td>
                                    <td><?= htmlspecialchars($role['permissions']) ?></td>
                                    <td>
                                        <button class="edit-btn btn btn-warning" data-role-id="<?= $role['role_id'] ?>">แก้ไข</button>
                                        <button class="delete-btn btn btn-danger" data-role-id="<?= $role['role_id'] ?>">ลบ</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>
</body>

</html>
