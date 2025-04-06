<?php
date_default_timezone_set("Asia/Bangkok");

include dirname(__FILE__) . '/../../config/connect_db.php';

$category_result = mysqli_query($conn, "SELECT * FROM category");
?>

<?php include dirname(__FILE__) . '/include/header.php'; ?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>รายการหมวดหมู่</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="row">
        <main class="main-wrapper col-md-9 ms-sm-auto py-4 col-lg-9 px-md-4 border-start">
            <div class="container mt-4">
                <h3 class="mb-4"><strong>รายการหมวดหมู่</strong></h3>
                <div class="mb-3 text-end">
                    <a href="add_category.php" class="btn btn-success">
                        <i class="fa-solid fa-plus"></i>
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table_use table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ลำดับ</th>
                                <th>ชื่อหมวดหมู่</th>
                                <th>คำอธิบาย</th>
                                <th>การจัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($category_result) > 0): ?>
                                <?php $no = 1;
                                while ($row = mysqli_fetch_assoc($category_result)): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= htmlspecialchars($row['category_name']) ?></td>
                                        <td><?= htmlspecialchars($row['description'])?></td>
                                        <td>
                                            <a href="edit_category.php?id=<?= $row['category_id'] ?>" class="btn btn-sm btn-warning">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <a href="delete_category.php?id=<?= $row['category_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบหมวดหมู่นี้?')">
                                                <i class="fa-solid fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center">ไม่พบข้อมูลหมวดหมู่</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <?php include 'include/footer.php'; ?>