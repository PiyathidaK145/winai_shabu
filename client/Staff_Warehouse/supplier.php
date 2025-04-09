<?php
date_default_timezone_set("Asia/Bangkok");
include dirname(__FILE__) . '/include/header.php';
include dirname(__FILE__) . '/../../config/connect_db.php';

// รับค่าการกรอง
$category_id = $_GET['category_id'] ?? '';
$item_name = $_GET['item_name'] ?? '';

// ดึงหมวดหมู่ทั้งหมด
$category_query = "SELECT DISTINCT c.category_id, c.category_name 
                   FROM category c 
                   JOIN raw_material rm ON c.category_id = rm.category_id 
                   JOIN supplier s ON rm.supplier_id = s.supplier_id";
$category_result = mysqli_query($conn, $category_query);

// ดึงเมนูตาม category_id
$menu_query = "SELECT DISTINCT rm.item_name 
               FROM raw_material rm 
               WHERE 1 ";
if (!empty($category_id)) {
    $menu_query .= "AND rm.category_id = '$category_id'";
}
$menu_result = mysqli_query($conn, $menu_query);

// ดึงข้อมูล supplier แบบ filter
$sql = "SELECT DISTINCT s.*
        FROM supplier s
        LEFT JOIN raw_material rm ON s.supplier_id = rm.supplier_id
        WHERE 1 ";
if (!empty($category_id)) {
    $sql .= "AND rm.category_id = '$category_id' ";
}
if (!empty($item_name)) {
    $sql .= "AND rm.item_name = '$item_name' ";
}
$sql .= "ORDER BY s.supplier_id ASC";
$suppliers = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>รายการซัพพลายเออร์</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="row">
        <main class="main-wrapper col-md-9 ms-sm-auto py-4 col-lg-9 px-md-4 border-start">
            <div class="container mt-4">
                <h3 class="mb-4"><strong>รายการซัพพลายเออร์</strong></h3>

                <!-- Filter Form -->
                <form method="GET" class="row mb-4 align-items-end">
                    <div class="col-md-4">
                        <label>หมวดหมู่</label>
                        <select name="category_id" class="form-select" onchange="this.form.submit()">
                            <option value="">ทั้งหมด</option>
                            <?php while ($cat = mysqli_fetch_assoc($category_result)) : ?>
                                <option value="<?= $cat['category_id'] ?>" <?= ($cat['category_id'] == $category_id) ? 'selected' : '' ?>>
                                    <?= $cat['category_name'] ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label>เมนู (วัตถุดิบ)</label>
                        <select name="item_name" class="form-select" onchange="this.form.submit()">
                            <option value="">ทั้งหมดใน category</option>
                            <?php while ($menu = mysqli_fetch_assoc($menu_result)) : ?>
                                <option value="<?= $menu['item_name'] ?>" <?= ($menu['item_name'] == $item_name) ? 'selected' : '' ?>>
                                    <?= $menu['item_name'] ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="col-md-4 text-end">
                        <label class="d-block">&nbsp;</label>
                        <a href="add_supplier.php" class="btn btn-success">
                            <i class="fa-solid fa-plus"></i>
                        </a>
                    </div>
                </form>


                <!-- Supplier Table -->
                <div class="table-responsive">
                    <table class="table_use table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ลำดับ</th>
                                <th>ชื่อซัพพลายเออร์</th>
                                <th>หมวดหมู่</th>
                                <th>โทรศัพท์</th>
                                <th>ที่อยู่</th>
                                <th>เว็บไซต์</th>
                                <th>การดำเนินการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($suppliers) > 0): ?>
                                <?php $no = 1;
                                while ($row = mysqli_fetch_assoc($suppliers)) : ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $row['name'] ?></td>
                                        <td>
                                            <?php
                                            $category_query = "SELECT category_name 
                                            FROM category 
                                            WHERE category_id = '" . $row['category_id'] . "'";

                                            $category_result = mysqli_query($conn, $category_query);
                                            $cat = mysqli_fetch_assoc($category_result);
                                            echo !empty($cat['category_name']) ? $cat['category_name'] : "-";

                                            ?>
                                        </td>
                                        <td><?= $row['phone'] ?></td>
                                        <td><?= $row['location'] ?></td>
                                        <td>
                                            <?php if (!empty($row['website'])): ?>
                                                <a href="<?= $row['website'] ?>" target="_blank" class="btn btn-sm btn-primary">เยี่ยมชม</a>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="edit_supplier.php?id=<?= $row['supplier_id'] ?>" class="btn btn-sm btn-warning"><i class="fa-solid fa-pen-to-square"></i></a>
                                            <a href="delete_supplier.php?id=<?= $row['supplier_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบ?')"><i class="fa-solid fa-trash"></i></a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center">ไม่พบข้อมูล</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <?php include dirname(__FILE__) . '/include/footer.php'; ?>
</body>

</html>