<?php
date_default_timezone_set("Asia/Bangkok");
include dirname(__FILE__) . '/include/header.php';
include dirname(__FILE__) . '/../../config/connect_db.php';

// รับค่าจังหวัดจาก query string
$selected_location = $_GET['location'] ?? '';
$where_clause = (!empty($selected_location)) ? "WHERE locations = '$selected_location'" : "";

// ดึงข้อมูลคลังวัตถุดิบตามจังหวัด
$warehouses = mysqli_query($conn, "SELECT * FROM warehouse $where_clause ORDER BY warehouse_id DESC");

// ดึงจังหวัดทั้งหมดสำหรับ dropdown
$locations_result = mysqli_query($conn, "SELECT DISTINCT locations FROM warehouse");
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>รายการคลังวัตถุดิบ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<div class="row">
    <main class="main-wrapper col-md-9 ms-sm-auto py-4 col-lg-9 px-md-4 border-start">
        <div class="container mt-4">
            <h3 class="mb-4"><strong>รายการคลังวัตถุดิบ</strong></h3>

            <!-- ฟอร์มฟิลเตอร์จังหวัด -->
            <form method="GET" class="mb-3">
                <div class="d-flex justify-content-between align-items-end">
                    <div style="max-width: 300px; width: 100%;">
                        <label for="location" class="form-label">จังหวัด</label>
                        <select name="location" id="location" class="form-select" onchange="this.form.submit()">
                            <option value="">ทั้งหมด</option>
                            <?php while ($loc = mysqli_fetch_assoc($locations_result)): ?>
                                <option value="<?= htmlspecialchars($loc['locations']) ?>" <?= ($selected_location == $loc['locations']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($loc['locations']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div>
                        <label class="form-label d-block invisible">เพิ่ม</label>
                        <a href="add_warehouse.php" class="btn btn-success">
                            <i class="fa-solid fa-plus"></i>
                        </a>
                    </div>
                </div>
            </form>

            <!-- ตารางแสดงคลังวัตถุดิบ -->
            <div class="table-responsive">
                <table class="table_use table-bordered table-hover">
                    <thead class="table-light">
                    <tr>
                        <th>ลำดับ</th>
                        <th>ชื่อคลังวัตถุดิบ</th>
                        <th>คำอธิบาย</th>
                        <th>ที่อยู่</th>
                        <th>การดำเนินการ</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (mysqli_num_rows($warehouses) > 0): ?>
                        <?php $no = 1; while ($row = mysqli_fetch_assoc($warehouses)) : ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($row['name']) ?></td>
                                <td><?= htmlspecialchars($row['discription']) ?></td>
                                <td><?= htmlspecialchars($row['locations']) ?></td>
                                <td>
                                    <a href="edit_warehouse.php?id=<?= $row['warehouse_id'] ?>" class="btn btn-sm btn-warning">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <a href="delete_warehouse.php?id=<?= $row['warehouse_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบ?')">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="text-center">ไม่พบข้อมูลคลังวัตถุดิบ</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<?php include dirname(__FILE__) . '/include/footer.php'; ?>
</body>
</html>
