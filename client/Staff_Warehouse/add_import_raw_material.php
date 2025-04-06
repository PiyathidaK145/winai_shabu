<?php
date_default_timezone_set("Asia/Bangkok");
include '../../config/connect_db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $menu_id = $_POST['menu_id'] ?? '';
    $quantity = $_POST['quantity'] ?? '';
    $cost = $_POST['cost'] ?? '';

    if (!empty($menu_id) && !empty($quantity) && !empty($cost)) {

        // Step 1: ดึงข้อมูลที่จำเป็นจาก menu และ raw_material
        $query = "
            SELECT m.quantity_of_sale, r.Num_before_consumption, r.raw_material_id, r.quanity AS rm_quantity
            FROM menu m 
            JOIN raw_material r ON m.raw_material_id = r.raw_material_id 
            WHERE m.menu_id = ?
        ";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $menu_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            $quantity_of_sale = $row['quantity_of_sale'];
            $num_before_consumption = $row['Num_before_consumption'];
            $raw_material_id = $row['raw_material_id'];
            $rm_quantity = $row['rm_quantity'];

            // Step 2: INSERT INTO import_raw_material
            $stmt_insert = mysqli_prepare($conn, "
                INSERT INTO import_raw_material (menu_id, quantity, cost) 
                VALUES (?, ?, ?)
            ");
            mysqli_stmt_bind_param($stmt_insert, "idd", $menu_id, $quantity, $cost);
            if (mysqli_stmt_execute($stmt_insert)) {
                $import_id = mysqli_insert_id($conn);

                // Step 3: คำนวณ capacity
                $capacity = intval(($quantity * $rm_quantity) / $quantity_of_sale);
                $expired_date = date('Y-m-d H:i:s', strtotime("+{$num_before_consumption} days"));
                $status = 'available';

                // Step 4: INSERT INTO calculate_raw_material
                $stmt_cal = mysqli_prepare($conn, "
                    INSERT INTO calculate_raw_material 
                    (import_raw_material_id, capacity, expried_date, status) 
                    VALUES (?, ?, ?, ?)
                ");
                mysqli_stmt_bind_param($stmt_cal, "iiss", $import_id, $capacity, $expired_date, $status);
                mysqli_stmt_execute($stmt_cal);

                // Step 5: อัปเดต raw_material.capacity
                $sum_query = "
                    SELECT SUM(capacity) AS total_capacity 
                    FROM calculate_raw_material 
                    JOIN import_raw_material irm ON irm.import_raw_material_id = calculate_raw_material.import_raw_material_id 
                    WHERE irm.menu_id = ?
                ";
                $stmt_sum = mysqli_prepare($conn, $sum_query);
                mysqli_stmt_bind_param($stmt_sum, "i", $menu_id);
                mysqli_stmt_execute($stmt_sum);
                $sum_result = mysqli_stmt_get_result($stmt_sum);
                $total_capacity = mysqli_fetch_assoc($sum_result)['total_capacity'];

                // อัปเดตค่า capacity ใน raw_material
                $update_rm = mysqli_prepare($conn, "UPDATE raw_material SET capacity = ? WHERE raw_material_id = ?");
                mysqli_stmt_bind_param($update_rm, "ii", $total_capacity, $raw_material_id);
                mysqli_stmt_execute($update_rm);

                // สำเร็จ
                header("Location: import_raw_material.php?success=1");
                exit;
            } else {
                $error = "ไม่สามารถบันทึกข้อมูลในตาราง import_raw_material";
            }
        } else {
            $error = "ไม่พบข้อมูลวัตถุดิบ";
        }
    } else {
        $error = "กรุณากรอกข้อมูลให้ครบถ้วน";
    }
}

// ดึงข้อมูล dropdown
$menu_result = mysqli_query($conn, "
    SELECT m.menu_id, r.item_name 
    FROM menu m 
    JOIN raw_material r ON m.raw_material_id = r.raw_material_id 
    ORDER BY r.item_name ASC
");

include dirname(__FILE__) . '/include/header.php';
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>เพิ่มรายการนำเข้าวัตถุดิบ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<div class="row">
    <main class="main-wrapper col-md-9 ms-sm-auto py-4 col-lg-9 px-md-4 border-start">
        <div class="container mt-4">
            <div class="card shadow">
                <div class="card-header bg-secondary text-white">
                    <h4 class="mb-0">เพิ่มรายการนำเข้าวัตถุดิบ</h4>
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>
                    <form method="POST">
                        <div class="mb-3">
                            <label for="menu_id" class="form-label">ชื่อวัตถุดิบ</label>
                            <select name="menu_id" id="menu_id" class="form-select" required>
                                <option value="">-- เลือกวัตถุดิบ --</option>
                                <?php while ($row = mysqli_fetch_assoc($menu_result)): ?>
                                    <option value="<?= $row['menu_id'] ?>"><?= htmlspecialchars($row['item_name']) ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="quantity" class="form-label">จำนวนที่นำเข้า</label>
                            <input type="number" step="0.01" name="quantity" id="quantity" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="cost" class="form-label">ราคา</label>
                            <input type="number" step="0.01" name="cost" id="cost" class="form-control" required>
                        </div>

                        <div class="text-end">
                            <a href="import_raw_material_list.php" class="btn btn-danger">ยกเลิก</a>
                            <button type="submit" class="btn btn-success">บันทึกข้อมูล</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>
</body>
</html>
