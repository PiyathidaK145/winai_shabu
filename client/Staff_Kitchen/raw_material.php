<?php
date_default_timezone_set("Asia/Bangkok");
include dirname(__FILE__) . '/include/header.php';
include dirname(__FILE__) . '/../../config/connect_db.php';

// รับค่าการกรอง
$category_id = $_GET['category_id'] ?? '';
$status_filter = $_GET['status'] ?? '';

// ดึงหมวดหมู่ทั้งหมด
$category_query = "SELECT DISTINCT c.category_id, c.category_name 
                   FROM category c 
                   JOIN raw_material rm ON c.category_id = rm.category_id 
                   JOIN supplier s ON rm.supplier_id = s.supplier_id";
$category_result = mysqli_query($conn, $category_query);

// ดึงเมนูตาม category_id
$menu_query = "SELECT DISTINCT rm.item_name FROM raw_material rm WHERE 1 ";
if (!empty($category_id)) {
    $menu_query .= "AND rm.category_id = '$category_id'";
}
$menu_result = mysqli_query($conn, $menu_query);

// SQL หลัก
$sql = "
    SELECT rm.*, w.name AS warehouse_name, m.quantity_of_sale AS amount_per_dish, m.unit
    FROM raw_material rm
    LEFT JOIN warehouse w ON rm.warehouse_id = w.warehouse_id
    LEFT JOIN menu m ON rm.raw_material_id = m.raw_material_id
    WHERE 1
";

if (!empty($category_id)) {
    $sql .= " AND rm.category_id = '$category_id'";
}

// เงื่อนไข filter ตาม status
if ($status_filter === 'available') {
    $sql .= " AND rm.status = 'available' AND rm.capacity > 20";
} elseif ($status_filter === 'low_stock') {
    $sql .= " AND rm.status = 'available' AND rm.capacity > 0 AND rm.capacity <= 20";
} elseif ($status_filter === 'out_of_stock') {
    $sql .= " AND (rm.status = 'out_of_stock' OR rm.capacity = 0)";
}

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>รายการวัตถุดิบ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="row">
        <main class="main-wrapper col-md-9 ms-sm-auto py-4 col-lg-9 px-md-4 border-start">
            <div class="container mt-4">
                <h3 class="mb-4"><strong>รายการวัตถุดิบ</strong></h3>

                <!-- Filter Form -->
                <form method="GET" class="row mb-4">
                    <div class="col-md-4">
                        <label for="category_id">หมวดหมู่</label>
                        <select name="category_id" id="category_id" class="form-select" onchange="this.form.submit()">
                            <option value="">ทั้งหมด</option>
                            <?php while ($cat = mysqli_fetch_assoc($category_result)) : ?>
                                <option value="<?= $cat['category_id'] ?>" <?= ($cat['category_id'] == $category_id) ? 'selected' : '' ?>>
                                    <?= $cat['category_name'] ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="status">สถานะ</label>
                        <select name="status" id="status" class="form-select" onchange="this.form.submit()">
                            <option value="">ทั้งหมด</option>
                            <option value="available" <?= ($status_filter == 'available') ? 'selected' : '' ?>>available</option>
                            <option value="low_stock" <?= ($status_filter == 'low_stock') ? 'selected' : '' ?>>low stock</option>
                            <option value="out_of_stock" <?= ($status_filter == 'out_of_stock') ? 'selected' : '' ?>>out of stock</option>
                        </select>
                    </div>
                </form>

                <!-- Raw Material Table -->
                <div class="table-responsive">
                    <table id="tableUse" class="table_use table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ลำดับ</th>
                                <th>ภาพประกอบ</th>
                                <th>ชื่อวัตถุดิบ</th>
                                <th onclick="sortTableByNumber()" style="cursor: pointer;">
                                    จำนวนจาน <i id="sortIcon" class="fa-solid fa-arrow-down"></i>
                                </th>
                                <th>ปริมาณต่อจาน</th>
                                <th>หน่วย</th>
                                <th>สถานะ</th>
                                <th>สถานที่เก็บ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($result) > 0): ?>
                                <?php $no = 1;
                                while ($row = mysqli_fetch_assoc($result)) : ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td>
                                            <?php if (!empty($row['image_url'])): ?>
                                                <img src="../../<?= $row['image_url'] ?>" width="60">
                                            <?php else: ?>
                                                -
                                            <?php endif; ?>
                                        </td>
                                        <td><?= htmlspecialchars($row['item_name']) ?></td>
                                        <td data-capacity="<?= htmlspecialchars($row['capacity']) ?>">
                                            <?= htmlspecialchars($row['capacity']) ?>
                                        </td>
                                        <td><?= htmlspecialchars($row['amount_per_dish'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($row['unit'] ?? '-') ?></td>
                                        <td>
                                            <?php
                                            if ($row['status'] == 'available') {
                                                if ($row['capacity'] > 20) {
                                                    echo '<span class="badge bg-success">available</span>';
                                                } elseif ($row['capacity'] > 0) {
                                                    echo '<span class="badge bg-warning text-dark">low stock</span>';
                                                } else {
                                                    echo '<span class="badge bg-danger">out of stock</span>';
                                                }
                                            } elseif ($row['status'] == 'out_of_stock') {
                                                echo '<span class="badge bg-danger">out of stock</span>';
                                            } else {
                                                echo '<span class="badge bg-secondary">ไม่ระบุ</span>';
                                            }
                                            ?>
                                        </td>
                                        <td><?= htmlspecialchars($row['warehouse_name']) ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center">ไม่พบข้อมูลวัตถุดิบ</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <?php include dirname(__FILE__) . '/include/footer.php'; ?>
    <script>
        let sortAsc = true;

        function sortTableByNumber() {
            const table = document.getElementById("tableUse");
            const tbody = table.querySelector("tbody");
            const rows = Array.from(tbody.querySelectorAll("tr"));

            rows.sort((a, b) => {
                const aCap = parseFloat(a.cells[3].dataset.capacity || 0);
                const bCap = parseFloat(b.cells[3].dataset.capacity || 0);
                return sortAsc ? aCap - bCap : bCap - aCap;
            });

            tbody.innerHTML = "";
            rows.forEach(row => tbody.appendChild(row));

            // เปลี่ยน icon
            const icon = document.getElementById("sortIcon");
            icon.classList.remove("fa-arrow-down", "fa-arrow-up");
            icon.classList.add(sortAsc ? "fa-arrow-up" : "fa-arrow-down");

            sortAsc = !sortAsc;
        }
    </script>
</body>

</html>