<?php
// สมมติฐานข้อมูลหมวดหมู่
$categories = [
    ["id" => 1, "name" => "Electronics", "description" => "Devices and gadgets"],
    ["id" => 2, "name" => "Clothing", "description" => "Apparel and accessories"],
    ["id" => 3, "name" => "Furniture", "description" => "Home and office furniture"],
    ["id" => 4, "name" => "Pork", "description" => "Pork-based items"],
    ["id" => 5, "name" => "Beef", "description" => "Beef-based items"]
];

// เริ่มต้นการกรอง
$filteredCategories = $categories;

// ตรวจสอบหากมีการกรองจาก checkbox
if (isset($_POST['filter_type']) && !empty($_POST['filter_type'])) {
    $filterTypes = $_POST['filter_type'];
    $filteredCategories = array_filter($categories, function ($category) use ($filterTypes) {
        // กรองประเภทที่ตรงกับที่เลือก
        return in_array($category['name'], $filterTypes);
    });
}

// Handle category deletion
if (isset($_GET['delete_id'])) {
    $delete_id = (int) $_GET['delete_id'];  // Sanitize and get the delete ID
    // Remove the category by ID
    $categories = array_filter($categories, function ($category) use ($delete_id) {
        return $category['id'] !== $delete_id;
    });

    // Redirect to prevent form resubmission
    header("Location: categories.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }

        h1 {
            text-align: center;
            margin-top: 30px;
        }

        .category-table {
            margin-top: 30px;
        }

        .btn-add {
            background-color: #007bff;
            color: #fff;
            margin-bottom: 20px;
        }

        .btn-add:hover {
            background-color: #0056b3;
        }

        .btn-back {
            background-color: #6c757d;
            color: #fff;
            margin-bottom: 20px;
        }

        .btn-back:hover {
            background-color: #5a6268;
        }

        .form-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .form-container input {
            flex: 1;
        }
    </style>
</head>

<body>
    <?php include 'include/header.php'; ?>

    <div class="container-fluid">
        <div class="row">
            <main class="main-wrapper col-md-9 ms-sm-auto py-4 col-lg-9 px-md-4 border-start">
                <div class="container">
                    <h1>ประเภทอาหาร</h1>
                    <!-- Add New Category Button -->
                    <a href="Add_categories.php" class="btn btn-primary mb-3">เพิ่มประเภทอาหาร</a>
                    <div class="mb-3 d-flex justify-content-end">
                        <button class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#filterModal">การกรอง</button>
                    </div>

                    <!-- Category Table -->
                    <div class="category-table">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>รายชื่อประเภทอาหาร</th>
                                    <th>คำอธิบาย</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($filteredCategories as $category) {
                                    echo "<tr>";
                                    echo "<td>" . $category['id'] . "</td>";
                                    echo "<td>" . htmlspecialchars($category['name']) . "</td>";
                                    echo "<td>" . htmlspecialchars($category['description']) . "</td>";
                                    echo "<td>
                                            <a href='Edit_categories.php?id=" . $category['id'] . "' class='btn btn-warning'>Edit</a>
                                            <a href='?delete_id=" . $category['id'] . "' class='btn btn-danger' onclick='return confirm(\"Are you sure you want to delete this category?\")'>Delete</a>
                                          </td>";
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Filter Modal -->
    <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filterModalLabel">กรองข้อมูลประเภทอาหาร</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="categories.php">
                        <div class="mb-3">
                            <label class="form-label">ประเภทอาหาร</label><br>
                            <input type="checkbox" id="pork" name="filter_type[]" value="Pork">
                            <label for="pork">หมู</label><br>
                            <input type="checkbox" id="beef" name="filter_type[]" value="Beef">
                            <label for="beef">เนื้อ</label><br>
                        </div>
                        <button type="submit" class="btn btn-primary">กรอง</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>

</html>
