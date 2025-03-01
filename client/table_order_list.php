<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_GET['ajax'])) {
    include 'include/header.php';
}

$servername = "localhost";
$username = "root";
$password = "123456";
$dbname = "a_shabu";

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

    if ($orderId && in_array($newStatus, ['in_progress', 'complete'])) {
        $stmt = $conn->prepare("UPDATE `order` SET status = :status WHERE order_id = :orderId");
        $stmt->execute(['status' => $newStatus, 'orderId' => $orderId]);
        echo json_encode(['success' => true, 'message' => 'Status updated successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid input.']);
    }
    exit;
}

// ✅ **ตั้งค่าการเรียงลำดับ**
$orderColumn = $_GET['orderColumn'] ?? 'getting_table_id';
$orderDirection = ($_GET['orderDirection'] ?? 'ASC') === 'ASC' ? 'ASC' : 'DESC';

// ✅ **กรองเฉพาะ Order ที่เป็น `in_progress`**
$whereClause = "WHERE o.status = 'in_progress'";
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
        o.status
    FROM `order` o
    LEFT JOIN getting_table g ON o.getting_table_id = g.getting_table_id
    LEFT JOIN reservation r ON g.reservation_id = r.reservation_id
    LEFT JOIN table_availability t ON r.availability_id = t.availability_id
    LEFT JOIN menu m ON o.menu_id = m.menu_id
    LEFT JOIN raw_material rm ON m.raw_material_id = rm.raw_material_id
    $whereClause
    GROUP BY g.getting_table_id, o.order_id, rm.item_name, o.quantity, o.order_date, o.status
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
    foreach (explode(', ', $row['table_numbers']) as $table) {
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
                            <td><?= htmlspecialchars($row['order_number']) ?></td>
                            <td><?= htmlspecialchars($row['table_numbers']) ?></td>
                            <td><?= htmlspecialchars($row['menu_item']) ?></td>
                            <td><?= htmlspecialchars($row['quantity']) ?></td>
                            <td><?= htmlspecialchars($row['order_date']) ?></td>
                            <td>
                                <select class="status-dropdown" data-order-id="<?= $row['order_id'] ?>">
                                    <option value="in_progress" <?= $row['status'] == 'in_progress' ? 'selected' : '' ?>>In Progress</option>
                                    <option value="complete" <?= $row['status'] == 'complete' ? 'selected' : '' ?>>Complete</option>
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
        "order": [[0, "asc"]],
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

        fetch("table_order_list.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `orderId=${orderId}&newStatus=${newStatus}`
        })
        .then(response => response.json())
        .then(data => console.log(data.message))
        .catch(error => console.error("Error:", error));
    });
});

</script>
