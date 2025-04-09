<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_GET['ajax'])) {
    include 'include/header.php';
}

include dirname(__FILE__) . '/../../config/connect_db.php';

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// ✅ **อัปเดตสถานะผ่าน AJAX**
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['orderId'], $_POST['newStatus'])) {
    $orderId = filter_input(INPUT_POST, 'orderId', FILTER_VALIDATE_INT);
    $newStatus = $_POST['newStatus'];

    if ($orderId && in_array($newStatus, ['pending', 'served'])) {
        // ✅ อัปเดตสถานะ
        $stmt = $conn->prepare("UPDATE `order` SET status_waiter = :status WHERE order_id = :orderId");
        $stmt->execute(['status' => $newStatus, 'orderId' => $orderId]);

        if ($newStatus === 'served') {
            $stmt = $conn->prepare("
                SELECT rm.item_name AS menu_item, o.quantity
                FROM `order` o
                JOIN menu m ON o.menu_id = m.menu_id
                JOIN raw_material rm ON m.raw_material_id = rm.raw_material_id
                WHERE o.order_id = :orderId
            ");
            $stmt->execute(['orderId' => $orderId]);
            $order = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($order) {
                $menuItem = $order['menu_item'];
                $quantityToDeduct = $order['quantity']; // 👈 ✅ ใช้ quantity จาก order

                // 1. หา raw_material_id จาก item_name
                $stmt = $conn->prepare("SELECT raw_material_id FROM raw_material WHERE item_name = :item_name");
                $stmt->execute(['item_name' => $menuItem]);
                $rawMaterialId = $stmt->fetchColumn();

                if ($rawMaterialId) {
                    // 2. หา menu_id ที่ใช้ raw_material นี้
                    $stmt = $conn->prepare("SELECT menu_id FROM menu WHERE raw_material_id = :raw_id");
                    $stmt->execute(['raw_id' => $rawMaterialId]);
                    $menuIds = $stmt->fetchAll(PDO::FETCH_COLUMN);

                    foreach ($menuIds as $menuId) {
                        // 3. หา import_raw_material_id
                        $stmt = $conn->prepare("SELECT import_raw_material_id FROM import_raw_material WHERE menu_id = :menuId");
                        $stmt->execute(['menuId' => $menuId]);
                        $importIds = $stmt->fetchAll(PDO::FETCH_COLUMN);

                        foreach ($importIds as $importId) {
                            $remaining = $quantityToDeduct;

                            ob_implicit_flush(true);  // เปิดการแสดงข้อความแบบทันที
                            // เปิดไฟล์เพื่อบันทึกข้อความ
                            $file = fopen("debug_log.txt", "a");
                            while ($remaining > 0) {
                                // ตรวจสอบว่า remaining == 0 ก่อนทำการคิวรี
                                if ($remaining == 0) {
                                    fwrite($file, "Remaining is 0, exiting loop.\n");
                                    break;  // หยุดลูป
                                }
                            
                                // คิวรีข้อมูลจากฐานข้อมูล
                                $stmt = $conn->prepare(" 
                                    SELECT calculate_raw_material_id, capacity, expried_date 
                                    FROM calculate_raw_material 
                                    WHERE import_raw_material_id = :importId AND capacity > 0 
                                    ORDER BY expried_date ASC LIMIT 1 
                                ");
                                $stmt->execute(['importId' => $importId]);
                                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                            
                                // หากไม่พบข้อมูล หรือ remaining == 0 ให้หยุดลูป
                                if (!$row) {
                                    fwrite($file, "No more rows found. Exiting loop.\n");
                                    break;  // หยุดลูป
                                }
                            
                                // แสดงข้อมูลแถวที่เจอ
                                fwrite($file, "Found row: calculate_raw_material_id = " . $row['calculate_raw_material_id'] . ", capacity = " . $row['capacity'] . ", expried_date = " . $row['expried_date'] . "\n");
                            
                                // หัก capacity ตาม remaining
                                $deductAmount = min($row['capacity'], $remaining);
                                $newCapacity = $row['capacity'] - $deductAmount;
                            
                                // แสดงการหัก capacity
                                fwrite($file, "Deducting: $deductAmount from capacity. New capacity = $newCapacity\n");
                            
                                // อัปเดต capacity ในฐานข้อมูล
                                $stmt = $conn->prepare(" 
                                    UPDATE calculate_raw_material 
                                    SET capacity = :newCapacity 
                                    WHERE calculate_raw_material_id = :id 
                                ");
                                $stmt->execute(['newCapacity' => $newCapacity, 'id' => $row['calculate_raw_material_id']]);
                            
                                // ตรวจสอบผลการอัปเดต
                                if ($stmt->rowCount() > 0) {
                                    fwrite($file, "Capacity updated successfully for calculate_raw_material_id = " . $row['calculate_raw_material_id'] . ".\n");
                                } else {
                                    fwrite($file, "No rows affected for calculate_raw_material_id = " . $row['calculate_raw_material_id'] . ".\n");
                                }
                            
                                // อัปเดต remaining
                                $remaining -= $deductAmount;
                            }
                            // ตรวจสอบว่าเมื่อออกจากลูปยังคงทำงาน
                            fwrite($file, "Exited loop, remaining = $remaining\n");
                            die();
                        }
                    }
                }
            }die();
        }
        echo json_encode(['success' => true, 'message' => 'Status updated and raw material adjusted.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid input.']);
    }
    exit;
}


// ✅ **ตั้งค่าการเรียงลำดับ**
$orderColumn = $_GET['orderColumn'] ?? 'getting_table_id';
$orderDirection = ($_GET['orderDirection'] ?? 'ASC') === 'ASC' ? 'ASC' : 'DESC';

// ✅ **กรองเฉพาะ Order ที่เป็น `pending`**
$whereClause = "WHERE o.status_waiter  = 'pending'";
$params = [];

if (!empty($_GET['table_id'])) {
    $tableId = intval($_GET['table_id']);
    $whereClause .= " AND t.table_id = :tableId";
    $params['tableId'] = $tableId;
}

// ✅ **Query ดึงข้อมูล Order**
$query = "
    SELECT
        o.order_id,
        g.getting_table_id,
        GROUP_CONCAT(DISTINCT t.table_id ORDER BY t.table_id ASC SEPARATOR ', ') AS table_numbers,
        rm.item_name AS menu_item,
        o.quantity,
        o.order_date,
        o.status_waiter
    FROM `order` o
    LEFT JOIN getting_table g ON o.getting_table_id = g.getting_table_id
    LEFT JOIN reservation r ON g.reservation_id = r.reservation_id
    LEFT JOIN walkin w ON g.walkin_id = w.walkin_id  -- เพิ่มการเชื่อมโยงตาราง walkin
    LEFT JOIN table_availability t ON (r.availability_id = t.availability_id OR w.availability_id = t.availability_id)  -- ใช้การเชื่อมโยงทั้งจาก reservation และ walkin
    LEFT JOIN menu m ON o.menu_id = m.menu_id
    LEFT JOIN raw_material rm ON m.raw_material_id = rm.raw_material_id
    $whereClause
    GROUP BY g.getting_table_id, o.order_id, rm.item_name, o.quantity, o.order_date, o.status_waiter 
    ORDER BY $orderColumn $orderDirection
";


$stmt = $conn->prepare($query);
$stmt->execute($params);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
$newOrderDirection = $orderDirection == 'ASC' ? 'DESC' : 'ASC';

// ✅ **สร้าง order_number**
$orderNumbers = [];
$orderCounter = 1;
$tableOptions = [];

foreach ($result as &$row) {
    $tableId = $row['getting_table_id'];

    if (!isset($orderNumbers[$tableId])) {
        $orderNumbers[$tableId] = str_pad($orderCounter++, 3, '0', STR_PAD_LEFT);
    }

    $row['order_number'] = $orderNumbers[$tableId];

    // ✅ เพิ่มค่าโต๊ะในตัวเลือกดรอปดาวน์โดยตรวจสอบค่าซ้ำ
    $tableNumbers = $row['table_numbers'] ?? ''; // หากเป็น null ให้ใช้ค่าว่างแทน
    foreach (explode(', ', $tableNumbers) as $table) {
        $tableOptions[$table] = $table;
    }
}
unset($row);

// ✅ **โหลดเฉพาะตารางผ่าน AJAX**
if (isset($_GET['ajax']) && $_GET['ajax'] == 1) {
    include 'table_content.php';
    exit;
}
?>

<!-- ✅ **DataTables Framework Integration** -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<div class="container-fluid">
    <div class="row">
        <main class="main-wrapper col-md-9 ms-sm-auto py-4 col-lg-9 px-md-4 border-start">
            <h1 class="h2 mb-3">รายการอาหารแต่ละโต๊ะ</h1>
            <div class="mb-3">
                <label for="tableFilter" class="form-label">เลือกโต๊ะ:</label>
                <select id="tableFilter" class="form-select">
                    <option value="">ทั้งหมด</option>
                    <?php foreach ($tableOptions as $tableId): ?>
                        <option value="<?= htmlspecialchars($tableId) ?>">
                            <?= htmlspecialchars($tableId) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="table-responsive">
                <table id="orderTable" class="display">
                    <thead>
                        <tr>
                            <th>Order Number</th>
                            <th>Table</th>
                            <th>Menu</th>
                            <th>Quantity</th>
                            <th>Order Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($result as $row): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['order_number'] ?? '') ?></td>
                                <td><?= htmlspecialchars($row['table_numbers'] ?? '') ?></td>
                                <td><?= htmlspecialchars($row['menu_item'] ?? '') ?></td>
                                <td><?= htmlspecialchars($row['quantity'] ?? '') ?></td>
                                <td><?= htmlspecialchars($row['order_date'] ?? '') ?></td>

                                <td>
                                    <select class="status-dropdown" data-order-id="<?= $row['order_id'] ?>">
                                        <option value="pending" <?= $row['status_waiter'] == 'pending' ? 'selected' : '' ?>>pending</option>
                                        <option value="served" <?= $row['status_waiter'] == 'served' ? 'selected' : '' ?>>served</option>
                                    </select>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>

<script>
    $(document).ready(function() {
        let table = $('#orderTable').DataTable({
            "order": [
                [0, "asc"]
            ],
            "columnDefs": [{
                "targets": [5],
                "orderable": false
            }]
        });

        // กรองตามหมายเลขโต๊ะที่เลือก
        $('#tableFilter').on('change', function() {
            let tableId = $(this).val();
            if (tableId) {
                table.column(1).search('\\b' + tableId + '\\b', true, false).draw();
            } else {
                table.column(1).search('').draw();
            }
        });

        // อัปเดตสถานะ order ผ่าน AJAX
        $(document).on("change", ".status-dropdown", function() {
            let orderId = $(this).data("order-id");
            let newStatus = $(this).val();
            let currentRow = $(this).closest('tr');

            fetch("table_order_list.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: `orderId=${orderId}&newStatus=${newStatus}`
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data.message);

                    if (newStatus === 'served') {
                        // ลบแถวทันทีจาก DataTable
                        $('#orderTable').DataTable().row(currentRow).remove().draw();
                    }
                })
                .catch(error => console.error("Error:", error));
        });
    })
</script>