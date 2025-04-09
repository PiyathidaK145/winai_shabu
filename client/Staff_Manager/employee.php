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
<<<<<<< HEAD
=======

    // ดึงข้อมูลตำแหน่ง
    $positionStmt = $conn->prepare("SELECT role_id, role_name FROM role");
    $positionStmt->execute();
    $positions = $positionStmt->fetchAll(PDO::FETCH_ASSOC);
>>>>>>> 8b2216fd18008dad437930077b67c9ef256e13d2
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

<<<<<<< HEAD
// รับค่าที่ส่งมาจากฟอร์มการกรอง
$filterPosition = isset($_POST['filter_position']) ? $_POST['filter_position'] : '';
$searchTerm = isset($_POST['search_name']) ? $_POST['search_name'] : '';

// เตรียมคำสั่ง SQL
$sql = "SELECT e.employee_id, e.first_name, e.last_name, e.email, e.phone,e.created_at, r.role_name
        FROM employee e
        JOIN role r ON e.role_id = r.role_id";

// กรองตามตำแหน่ง
if (!empty($filterPosition)) {
    $sql .= " WHERE r.role_name = :role_name";
}

// ค้นหาชื่อพนักงาน
=======
// รับค่าจากฟอร์ม
$filterPosition = isset($_POST['filter_position']) ? $_POST['filter_position'] : '';
$searchTerm = isset($_POST['search_name']) ? $_POST['search_name'] : '';

// สร้าง SQL
$sql = "SELECT e.employee_id, e.first_name, e.last_name, e.email, e.phone, e.created_at, r.role_name
        FROM employee e
        JOIN role r ON e.role_id = r.role_id";

// เงื่อนไขกรอง
if (!empty($filterPosition)) {
    $sql .= " WHERE r.role_name = :role_name";
}
>>>>>>> 8b2216fd18008dad437930077b67c9ef256e13d2
if (!empty($searchTerm)) {
    $sql .= (empty($filterPosition) ? " WHERE " : " AND ") . "(e.first_name LIKE :search_name OR e.last_name LIKE :search_name)";
}

<<<<<<< HEAD

$stmt = $conn->prepare($sql);

// ผูกพารามิเตอร์
=======
$stmt = $conn->prepare($sql);

// Binding
>>>>>>> 8b2216fd18008dad437930077b67c9ef256e13d2
if (!empty($filterPosition)) {
    $stmt->bindParam(':role_name', $filterPosition, PDO::PARAM_STR);
}
if (!empty($searchTerm)) {
<<<<<<< HEAD
    // ใส่เครื่องหมาย % สำหรับการค้นหาแบบ LIKE
=======
>>>>>>> 8b2216fd18008dad437930077b67c9ef256e13d2
    $searchTermWithWildcards = "%$searchTerm%";
    $stmt->bindParam(':search_name', $searchTermWithWildcards, PDO::PARAM_STR);
}

<<<<<<< HEAD
// ดำเนินการคำสั่ง SQL
$stmt->execute();

// ดึงข้อมูลผลลัพธ์
=======
$stmt->execute();
>>>>>>> 8b2216fd18008dad437930077b67c9ef256e13d2
$staffList = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<<<<<<< HEAD

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
=======
<head>
    <meta charset="UTF-8">
>>>>>>> 8b2216fd18008dad437930077b67c9ef256e13d2
    <title>Staff List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
<<<<<<< HEAD

=======
>>>>>>> 8b2216fd18008dad437930077b67c9ef256e13d2
        table {
            width: 100%;
            margin-top: 30px;
            border-collapse: collapse;
        }
<<<<<<< HEAD

        table th,
        table td {
=======
        table th, table td {
>>>>>>> 8b2216fd18008dad437930077b67c9ef256e13d2
            padding: 15px;
            text-align: center;
            border: 1px solid #ddd;
        }
<<<<<<< HEAD

        table th {
            background-color: #f8f9fa;
        }

=======
        table th {
            background-color: #f8f9fa;
        }
>>>>>>> 8b2216fd18008dad437930077b67c9ef256e13d2
        .btn-action {
            margin: 0 5px;
            border-radius: 5px;
        }
<<<<<<< HEAD

=======
>>>>>>> 8b2216fd18008dad437930077b67c9ef256e13d2
        .btn-add {
            background-color: #007bff;
            color: #fff;
            margin-bottom: 20px;
            border-radius: 5px;
        }
    </style>
    <script>
<<<<<<< HEAD
document.addEventListener('DOMContentLoaded', () => {
    const dateHeader = document.querySelector('.sortable-date');
    const sortIcon = document.getElementById('sort-icon');
    let asc = false; // เริ่มต้นให้เรียงจากใหม่ -> เก่า

    dateHeader.addEventListener('click', () => {
        const table = dateHeader.closest('table');
        const rows = Array.from(table.querySelectorAll('tbody > tr'));

        rows.sort((a, b) => {
            const dateA = new Date(a.children[5].innerText.trim());
            const dateB = new Date(b.children[5].innerText.trim());
            return asc ? dateA - dateB : dateB - dateA;
        });

        rows.forEach(row => table.querySelector('tbody').appendChild(row));
        asc = !asc;

        // เปลี่ยนไอคอนลูกศร
        sortIcon.textContent = asc ? '🔼' : '🔽';
    });
});
</script>

</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <main class="main-wrapper col-md-9 ms-sm-auto py-4 col-lg-9 px-md-4 border-start">
                <div class="container mt-5">
                    <h1 class="h2 mb-0">รายชื่อพนักงาน</h1>
                    <div class="row">
                        <div class="mb-3 d-flex justify-content-between align-items-center">
                            <div class="d-flex">
                                <a href="Add_Staff.php" class="btn btn-primary me-2 w-auto py-2">เพิ่มพนักงาน</a>
                                <button class="btn btn-outline-dark me-2 w-auto py-2" data-bs-toggle="modal" data-bs-target="#filterModal">การกรอง</button>
                            </div>
                            <div class="d-flex">
                                <form method="POST" class="d-flex align-items-center">
                                    <input type="text" name="search_name" class="form-control me-2" placeholder="ค้นหาชื่อพนักงาน" value="<?php echo htmlspecialchars($searchTerm); ?>">
                                    <button type="submit" class="btn btn-outline-dark me-2 w-auto py-2">ค้นหา</button>
                                    <a href="Staff.php" class="btn btn-secondary me-2 w-auto py-2">ล้าง</a>
                                </form>
                            </div>
                        </div>
                    </div>
                    <table class="table-responsive">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>ชื่อ-นามสกุล</th>
                                <th>หน้าที่</th>
                                <th>อีเมล</th>
                                <th>เบอร์โทร</th>
                                <th class="sortable-date" style="cursor: pointer;">เข้าทำงาน <span id="sort-icon">⇅</span></th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (empty($staffList)) {
                                echo "<tr><td colspan='6'>ไม่มีข้อมูลพนักงานที่ตรงกับเงื่อนไขการค้นหา</td></tr>";
                            } else {
                                foreach ($staffList as $staff) {
                                    echo "<tr>";
                                    echo "<td>{$staff['employee_id']}</td>";
                                    echo "<td>{$staff['first_name']} {$staff['last_name']}</td>";
                                    echo "<td>{$staff['role_name']}</td>";
                                    echo "<td>{$staff['email']}</td>";
                                    echo "<td>{$staff['phone']}</td>";
                                    echo "<td>{$staff['created_at']}</td>";
                                    echo "<td>
                                        <a href='Edit_employee.php?id={$staff['employee_id']}' class='btn btn-warning btn-sm btn-action'>Edit</a>
                                        <a href='delete_employee.php?id={$staff['employee_id']}' class='btn btn-danger btn-sm btn-action' onclick='return confirm(\"Are you sure you want to delete this record?\")'>Delete</a>
                                    </td>";
                                    echo "</tr>";
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>

        <!-- Filter Modal -->
        <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="filterModalLabel">กรองข้อมูลพนักงาน</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                </div>
            </div>
        </div>
    </div>
</body>

=======
    document.addEventListener('DOMContentLoaded', () => {
        const dateHeader = document.querySelector('.sortable-date');
        const sortIcon = document.getElementById('sort-icon');
        let asc = false;

        dateHeader.addEventListener('click', () => {
            const table = dateHeader.closest('table');
            const rows = Array.from(table.querySelectorAll('tbody > tr'));

            rows.sort((a, b) => {
                const dateA = new Date(a.children[5].innerText.trim());
                const dateB = new Date(b.children[5].innerText.trim());
                return asc ? dateA - dateB : dateB - dateA;
            });

            rows.forEach(row => table.querySelector('tbody').appendChild(row));
            asc = !asc;
            sortIcon.textContent = asc ? '🔼' : '🔽';
        });
    });
    </script>
</head>

<body>
<div class="container-fluid">
    <div class="row">
        <main class="main-wrapper col-md-9 ms-sm-auto py-4 col-lg-9 px-md-4 border-start">
            <div class="container mt-5">
                <h1 class="h2 mb-0">รายชื่อพนักงาน</h1>
                <div class="row">
                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <div class="d-flex">
                            <a href="Add_employee.php" class="btn btn-primary me-2">เพิ่มพนักงาน</a>
                            <button class="btn btn-outline-dark me-2" data-bs-toggle="modal" data-bs-target="#filterModal">การกรอง</button>
                        </div>
                        <form method="POST" class="d-flex align-items-center">
                            <input type="hidden" name="filter_position" value="<?= htmlspecialchars($filterPosition) ?>">
                            <input type="text" name="search_name" class="form-control me-2" placeholder="ค้นหาชื่อพนักงาน" value="<?= htmlspecialchars($searchTerm) ?>">
                            <button type="submit" class="btn btn-outline-dark me-2">ค้นหา</button>
                            <a href="Staff.php" class="btn btn-secondary">ล้าง</a>
                        </form>
                    </div>
                </div>

                <table class="table-responsive">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>ชื่อ-นามสกุล</th>
                        <th>หน้าที่</th>
                        <th>อีเมล</th>
                        <th>เบอร์โทร</th>
                        <th class="sortable-date" style="cursor: pointer;">เข้าทำงาน <span id="sort-icon">⇅</span></th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($staffList)) : ?>
                        <tr><td colspan='7'>ไม่มีข้อมูลพนักงานที่ตรงกับเงื่อนไข</td></tr>
                    <?php else : ?>
                        <?php foreach ($staffList as $staff): ?>
                            <tr>
                                <td><?= $staff['employee_id'] ?></td>
                                <td><?= htmlspecialchars($staff['first_name'] . ' ' . $staff['last_name']) ?></td>
                                <td><?= htmlspecialchars($staff['role_name']) ?></td>
                                <td><?= htmlspecialchars($staff['email']) ?></td>
                                <td><?= htmlspecialchars($staff['phone']) ?></td>
                                <td><?= htmlspecialchars($staff['created_at']) ?></td>
                                <td>
                                    <a href="Edit_employee.php?id=<?= $staff['employee_id'] ?>" class="btn btn-warning btn-sm btn-action">Edit</a>
                                    <a href="delete_employee.php?id=<?= $staff['employee_id'] ?>" class="btn btn-danger btn-sm btn-action" onclick="return confirm('Are you sure you want to delete this record?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <!-- Filter Modal -->
    <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filterModalLabel">กรองข้อมูลพนักงาน</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="filter_position" class="form-label">ตำแหน่ง</label>
                        <select name="filter_position" class="form-select" id="filter_position">
                            <option value="">-- ทั้งหมด --</option>
                            <?php foreach ($positions as $role): ?>
                                <option value="<?= htmlspecialchars($role['role_name']) ?>" <?= ($filterPosition == $role['role_name']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($role['role_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">กรอง</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
>>>>>>> 8b2216fd18008dad437930077b67c9ef256e13d2
</html>
