<?php
// Mock Data for staff members (ตัวอย่างข้อมูลพนักงาน)
$staffList = [
    ['id' => 1, 'name' => 'โมนา ลิซ่า', 'position' => 'พนักงานต้อนรับ', 'email' => 'john.doe@example.com', 'phone' => '090-xxxxxxx'],
    ['id' => 2, 'name' => 'วางยา ยาพิษ', 'position' => 'พนักงานเสิร์ฟ', 'email' => 'jane.smith@example.com', 'phone' => '090-xxxxxxx'],
    ['id' => 3, 'name' => 'อดัม อีฟ', 'position' => 'พนักงานทำความสะอาด', 'email' => 'david.brown@example.com', 'phone' => '090-xxxxxxx'],
    ['id' => 4, 'name' => 'หญิงลี ชุลีพร', 'position' => 'พนักงานเสิร์ฟ', 'email' => 'emily.davis@example.com', 'phone' => '090-xxxxxxx'],
    ['id' => 5, 'name' => 'ลมพัด ดังตึ้ง', 'position' => 'พนักงานเสิร์ฟ', 'email' => 'michael.johnson@example.com', 'phone' => '090-xxxxxxx'],
    ['id' => 6, 'name' => 'กระแต อาร์สยาม', 'position' => 'พนักงานเสิร์ฟ', 'email' => 'sarah.wilson@example.com', 'phone' => '090-xxxxxxx'],
    ['id' => 7, 'name' => 'สตีฟ จอบ', 'position' => 'พนักงานทำความสะอาด', 'email' => 'chris.lee@example.com', 'phone' => '090-xxxxxxx'],
    ['id' => 8, 'name' => 'ตายวันไหน วันนี้', 'position' => 'พนักงานเคาท์เตอร์', 'email' => 'laura.martinez@example.com', 'phone' => '090-xxxxxxx'],
    ['id' => 9, 'name' => 'ทหารแบกปูน ไปโบกตึก', 'position' => 'พนักงานครัว', 'email' => 'james.taylor@example.com', 'phone' => '090-xxxxxxx'],
    ['id' => 10, 'name' => 'ลิซ่า แบล็คพิ้ง', 'position' => 'พนักงานทำความสะอาด', 'email' => 'anna.scott@example.com', 'phone' => '090-xxxxxxx'],
];

// รับค่าที่ส่งมาจากฟอร์มการกรอง
$filterPosition = isset($_POST['filter_position']) ? $_POST['filter_position'] : '';
$searchTerm = isset($_POST['search_name']) ? $_POST['search_name'] : '';

// กรองข้อมูลตามตำแหน่งที่เลือก
if (!empty($filterPosition)) {
    $filteredStaffList = array_filter($staffList, function ($staff) use ($filterPosition) {
        return $staff['position'] === $filterPosition;
    });
} else {
    $filteredStaffList = $staffList; // หากไม่ได้กรอง ให้แสดงข้อมูลทั้งหมด
}

// ค้นหาข้อมูลตามชื่อพนักงาน
if (!empty($searchTerm)) {
    $filteredStaffList = array_filter($filteredStaffList, function ($staff) use ($searchTerm) {
        return stripos($staff['name'], $searchTerm) !== false; // ค้นหาชื่อพนักงานโดยไม่สนใจตัวพิมพ์ใหญ่หรือเล็ก
    });
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script> <!-- Bootstrap JS -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }

        .container {
            margin-top: 30px;
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
            background-color: #f8f9fa;
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

        .filter-form {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <?php include 'include/header.php'; ?>
    <div class="container-fluid">
        <div class="row">
            <main class="main-wrapper col-md-9 ms-sm-auto py-4 col-lg-9 px-md-4 border-start">
                <div class="container mt-5">
                    <h1 class="h2 mb-0">รายชื่อพนักงาน</h1>
                    <div class="row">
                        <div class="mb-3 d-flex justify-content-between align-items-center">
                            <!-- Left Section: Buttons -->
                            <div class="d-flex">
                                <a href="Add_Staff.php" class="btn btn-primary me-2 w-auto py-2">เพิ่มพนักงาน</a>
                                <button class="btn btn-outline-dark me-2 w-auto py-2" data-bs-toggle="modal" data-bs-target="#filterModal">การกรอง</button>
                            </div>
                            <!-- Right Section: Search Form -->
                            <div class="d-flex">
                                <form method="POST" class="d-flex align-items-center">
                                    <input type="text" name="search_name" class="form-control me-2" placeholder="ค้นหาชื่อพนักงาน" value="<?php echo htmlspecialchars($searchTerm); ?>">
                                    <button type="submit" class="btn btn-outline-dark me-2 w-auto py-2">ค้นหา</button>
                                    <a href="Staff.php" class="btn btn-secondary me-2 w-auto py-2">ล้าง</a>
                                </form>
                            </div>
                        </div>
                    </div>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>ชื่อ-นามสกุล</th>
                                <th>หน้าที่</th>
                                <th>อีเมล</th>
                                <th>เบอร์โทร</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (empty($filteredStaffList)) {
                                echo "<tr><td colspan='6'>ไม่มีข้อมูลพนักงานที่ตรงกับเงื่อนไขการค้นหา</td></tr>";
                            } else {
                                foreach ($filteredStaffList as $staff) {
                                    echo "<tr>";
                                    echo "<td>{$staff['id']}</td>";
                                    echo "<td>{$staff['name']}</td>";
                                    echo "<td>{$staff['position']}</td>";
                                    echo "<td>{$staff['email']}</td>";
                                    echo "<td>{$staff['phone']}</td>";
                                    echo "<td>
                                        <a href='Edit_staff.php?id={$staff['id']}' class='btn btn-warning btn-sm btn-action'>Edit</a>
                                        <a href='delete_staff.php?id={$staff['id']}' class='btn btn-danger btn-sm btn-action' onclick='return confirm(\"Are you sure you want to delete this record?\")'>Delete</a>
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
                    <div class="modal-body">
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label class="form-label">กรองตามหน้าที่</label>
                                <select name="filter_position" class="form-select">
                                    <option value="">-- เลือกหน้าที่ --</option>
                                    <option value="พนักงานต้อนรับ" <?php echo ($filterPosition == 'พนักงานต้อนรับ') ? 'selected' : ''; ?>>พนักงานต้อนรับ</option>
                                    <option value="พนักงานเสิร์ฟ" <?php echo ($filterPosition == 'พนักงานเสิร์ฟ') ? 'selected' : ''; ?>>พนักงานเสิร์ฟ</option>
                                    <option value="พนักงานทำความสะอาด" <?php echo ($filterPosition == 'พนักงานทำความสะอาด') ? 'selected' : ''; ?>>พนักงานทำความสะอาด</option>
                                    <option value="พนักงานเคาท์เตอร์" <?php echo ($filterPosition == 'พนักงานเคาท์เตอร์') ? 'selected' : ''; ?>>พนักงานเคาท์เตอร์</option>
                                    <option value="พนักงานครัว" <?php echo ($filterPosition == 'พนักงานครัว') ? 'selected' : ''; ?>>พนักงานครัว</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">กรอง</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>