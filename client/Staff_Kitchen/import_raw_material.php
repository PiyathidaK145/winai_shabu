<?php
// ตั้งค่า timezone และ include ไฟล์เชื่อมต่อ

date_default_timezone_set("Asia/Bangkok");
include dirname(__FILE__) . '/include/header.php';
include dirname(__FILE__) . '/../../config/connect_db.php';

$category_id = $_GET['category_id'] ?? '';
$warehouse_name = $_GET['warehouse_name'] ?? '';
$status_filter = $_GET['status'] ?? '';
$start_date = $_GET['start_date'] ?? '';
$end_date = $_GET['end_date'] ?? '';

// ดึงข้อมูล filter dropdown
$category_result = mysqli_query($conn, "SELECT category_id, category_name FROM category");
$warehouse_result = mysqli_query($conn, "SELECT DISTINCT w.name FROM warehouse w JOIN raw_material rm ON w.warehouse_id = rm.warehouse_id");

$update_sql = "SELECT calculate_raw_material_id, expried_date FROM calculate_raw_material";
$update_result = mysqli_query($conn, $update_sql);
$today = new DateTime();

while ($row = mysqli_fetch_assoc($update_result)) {
    $expired_date = new DateTime($row['expried_date']);
    $interval = $today->diff($expired_date)->format('%r%a');

    if ($interval < 0) {
        $status = 'expired';
    } elseif ($interval <= 2) {
        $status = 'near_expiry';
    } else {
        $status = 'available';
    }

    // อัปเดตสถานะในฐานข้อมูล
    $update_stmt = mysqli_prepare($conn, "UPDATE calculate_raw_material SET status = ? WHERE calculate_raw_material_id = ?");
    mysqli_stmt_bind_param($update_stmt, "si", $status, $row['calculate_raw_material_id']);
    mysqli_stmt_execute($update_stmt);
}
// สร้าง SQL หลัก
$sql = "
SELECT 
    rm.item_name,
    crm.capacity,
    irm.cost,
    irm.create_at,
    m.quantity_of_sale,
    m.unit,
    DATE_ADD(irm.create_at, INTERVAL rm.Num_before_consumption DAY) AS expired_date
FROM 
    calculate_raw_material AS crm
JOIN import_raw_material AS irm ON crm.import_raw_material_id = irm.import_raw_material_id
JOIN menu AS m ON irm.menu_id = m.menu_id
JOIN raw_material AS rm ON m.raw_material_id = rm.raw_material_id
JOIN warehouse AS w ON rm.warehouse_id = w.warehouse_id
JOIN category AS c ON rm.category_id = c.category_id
WHERE 1
";

$params = [];
$types = '';

if (!empty($category_id)) {
    $sql .= " AND c.category_id = ?";
    $params[] = $category_id;
    $types .= 'i';
}

if (!empty($warehouse_name)) {
    $sql .= " AND w.name = ?";
    $params[] = $warehouse_name;
    $types .= 's';
}

if (!empty($start_date)) {
    $sql .= " AND irm.create_at >= ?";
    $params[] = $start_date . ' 00:00:00';
    $types .= 's';
}

if (!empty($end_date)) {
    $sql .= " AND irm.create_at <= ?";
    $params[] = $end_date . ' 23:59:59';
    $types .= 's';
}

$stmt = mysqli_prepare($conn, $sql);
if (!empty($params)) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// ดึงข้อมูลเพื่ออัปเดตสถานะ

?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>รายการนำเข้าวัตถุดิบ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="row">
        <main class="main-wrapper col-md-9 ms-sm-auto py-4 col-lg-9 px-md-4 border-start">
            <div class="container mt-4">
                <h3 class="mb-4"><strong>รายการนำเข้าวัตถุดิบ</strong></h3>

                <form method="GET" class="row mb-4 g-3 align-items-end">
                    <div class="col-md-2">
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

                    <div class="col-md-2">
                        <label>คลังวัตถุดิบ</label>
                        <select name="warehouse_name" class="form-select" onchange="this.form.submit()">
                            <option value="">ทั้งหมด</option>
                            <?php while ($wh = mysqli_fetch_assoc($warehouse_result)) : ?>
                                <option value="<?= $wh['name'] ?>" <?= ($wh['name'] == $warehouse_name) ? 'selected' : '' ?>>
                                    <?= $wh['name'] ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label>วันที่เริ่มต้น</label>
                        <input type="date" name="start_date" class="form-control" value="<?= $start_date ?>">
                    </div>

                    <div class="col-md-2">
                        <label>วันที่สิ้นสุด</label>
                        <input type="date" name="end_date" class="form-control" value="<?= $end_date ?>">
                    </div>

                    <div class="col-md-2">
                        <label>สถานะ</label>
                        <select name="status" class="form-select" onchange="this.form.submit()">
                            <option value="">ทั้งหมด</option>
                            <option value="available" <?= ($status_filter == 'available') ? 'selected' : '' ?>>available</option>
                            <option value="near_expiry" <?= ($status_filter == 'near_expiry') ? 'selected' : '' ?>>near_expiry</option>
                            <option value="expired" <?= ($status_filter == 'expired') ? 'selected' : '' ?>>expired</option>
                        </select>
                    </div>
                </form>

                <div class="table-responsive">
                    <table id="tableUse" class="table_use table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ลำดับ</th>
                                <th>ชื่อวัตถุดิบ</th>
                                <th onclick="sortTableByNumber(2, 'number')" style="cursor:pointer;">
                                    จำนวนจาน <i id="sortIconDish" class="fa-solid fa-arrow-down"></i>
                                </th>
                                <th onclick="sortTableByNumber(3, 'number')" style="cursor:pointer;">
                                    ปริมาณการสั่งซื้อ <i id="sortIconQuentity" class="fa-solid fa-arrow-down"></i>
                                </th>
                                <th>หน่วย</th>
                                <th onclick="sortTableByNumber(5, 'number')" style="cursor:pointer;">
                                    ราคา <i id="sortIconPrice" class="fa-solid fa-arrow-down"></i>
                                </th>
                                <th onclick="sortTableByNumber(6, 'date')" style="cursor:pointer;">
                                    วันที่ได้รับสินค้า <i id="sortIconReceived" class="fa-solid fa-arrow-down"></i>
                                </th>
                                <th onclick="sortTableByNumber(7, 'date')" style="cursor:pointer;">
                                    วันหมดอายุ <i id="sortIconExpired" class="fa-solid fa-arrow-down"></i>
                                </th>
                                <th>สถานะ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $today = new DateTime();
                            while ($row = mysqli_fetch_assoc($result)) {
                                $expired = new DateTime($row['expired_date']);
                                $interval = $today->diff($expired)->format('%r%a');

                                if ($interval < 0) {
                                    $status = 'expired';
                                    $color = 'text-danger';
                                    $text = 'expired';
                                } elseif ($interval <= 2) {
                                    $status = 'near_expiry';
                                    $color = 'text-warning';
                                    $text = 'near_expiry';
                                } else {
                                    $status = 'available';
                                    $color = 'text-success';
                                    $text = 'available';
                                }

                                // กรองตามสถานะ
                                if (!empty($status_filter) && $status_filter !== $status) continue;
                            ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= htmlspecialchars($row['item_name']) ?></td>
                                    <td><?= htmlspecialchars($row['capacity']) ?></td>
                                    <td><?= htmlspecialchars($row['capacity']*$row['quantity_of_sale']) ?></td>
                                    <td><?= htmlspecialchars($row['unit'] ?? '-') ?></td>
                                    <td><?= number_format($row['cost'], 2) ?> บาท</td>
                                    <td><?= date('Y-m-d', strtotime($row['create_at'])) ?></td>
                                    <td><?= date('Y-m-d', strtotime($row['expired_date'])) ?></td>
                                    <td class="fw-bold <?= $color ?>"><?= $text ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
    <script>
        let sortState = {
            2: true,
            3: true,
            4: true,
            5: true,
            6: true,
            7: true
        };

        function sortTableByNumber(colIndex, type) {
            const table = document.getElementById("tableUse");
            const tbody = table.querySelector("tbody");
            const rows = Array.from(tbody.querySelectorAll("tr"));

            rows.sort((a, b) => {
                let valA = a.cells[colIndex].innerText.trim();
                let valB = b.cells[colIndex].innerText.trim();

                if (type === 'number') {
                    valA = parseFloat(valA.replace(/[^\d.]/g, '')) || 0;
                    valB = parseFloat(valB.replace(/[^\d.]/g, '')) || 0;
                } else if (type === 'date') {
                    valA = new Date(valA);
                    valB = new Date(valB);
                }

                return sortState[colIndex] ? valA - valB : valB - valA;
            });

            tbody.innerHTML = "";
            rows.forEach(row => tbody.appendChild(row));

            // Reset icon in all columns
            const iconIds = {
                2: "sortIconDish",
                3: "sortIconQuentity",
                4: "sortIconReceived",
                5: "sortIconExpired",
                6: "sortIconPrice",
                7: "sortIconExpired"
            };

            for (const [index, id] of Object.entries(iconIds)) {
                const icon = document.getElementById(id);
                if (icon) {
                    icon.classList.remove("fa-arrow-up", "fa-arrow-down");
                    icon.classList.add("fa-arrow-down"); // reset ทุกอันให้ลง
                }
            }

            // Set icon for the current column only
            const currentIcon = document.getElementById(iconIds[colIndex]);
            if (currentIcon) {
                currentIcon.classList.remove("fa-arrow-down", "fa-arrow-up");
                currentIcon.classList.add(sortState[colIndex] ? "fa-arrow-up" : "fa-arrow-down");
            }

            sortState[colIndex] = !sortState[colIndex];
        }
    </script>
</body>

</html>