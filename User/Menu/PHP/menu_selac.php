<?php
$host = "localhost";
$user = "root";
$password = "123456";
$database = "winaishabu";

define('BASE_URL', 'http://localhost:8081/winai-customer-main-main/User/Menu/img/');

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("\u274c การเชื่อมต่อล้มเหลว: " . $conn->connect_error);
}
mysqli_set_charset($conn, "utf8");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reservation_id'])) {
    $reservation_id = intval($_POST['reservation_id']);
    $sql = "SELECT package_id FROM getting_table WHERE reservation_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $reservation_id);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($package_id);
        $stmt->fetch();
    } else {
        die("\u274c ไม่พบรหัสการจองในระบบ");
    }
    $stmt->close();
} elseif (isset($_GET['reservation_id']) && isset($_GET['package_id'])) {
    $reservation_id = intval($_GET['reservation_id']);
    $package_id = intval($_GET['package_id']);
} else {
    die("\u274c ไม่พบข้อมูลการจอง");
}

// Query ดึงข้อมูลเมนู พร้อมแยกตามหมวดหมู่ (category)
$sql = "SELECT rm.raw_material_id, rm.item_name, rm.image_url, c.category_name
FROM package_item pi
JOIN menu mi ON pi.menu_id = mi.menu_id
JOIN raw_material rm ON mi.raw_material_id = rm.raw_material_id
JOIN category c ON rm.category_id = c.category_id
WHERE pi.package_id = ?
ORDER BY c.category_name ;

"; // เรียงตามหมวดหมู่

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $package_id);
$stmt->execute();
$result = $stmt->get_result();

// สร้างอาร์เรย์แยกตามหมวดหมู่
$groupedMenu = [];
while ($row = $result->fetch_assoc()) {
    $category = $row['category_name'];
    if (!isset($groupedMenu[$category])) {
        $groupedMenu[$category] = [];
    }
    $groupedMenu[$category][] = $row;
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Winai's Shabu</title>
    <link rel="stylesheet" href="../CSS/style.css">
    <script src="../javascript/scripts.js" defer></script>
</head>
<body>
    <main>
        <section id="menu-section">
            <?php foreach ($groupedMenu as $category => $items): ?>
                <h3><?php echo htmlspecialchars($category); ?></h3>
                <ul class="menu-list">
                    <?php foreach ($items as $item): ?>
                        <li class="menu-item">
                            <img src="<?php echo BASE_URL . htmlspecialchars($item['image_url']); ?>" width="100">
                            <p><?php echo htmlspecialchars($item['item_name']); ?></p>
                            <button onclick="updateOrder(<?php echo $item['raw_material_id']; ?>, '<?php echo htmlspecialchars($item['item_name']); ?>', 1)">+</button>
                            <button onclick="updateOrder(<?php echo $item['raw_material_id']; ?>, '<?php echo htmlspecialchars($item['item_name']); ?>', -1)">-</button>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endforeach; ?>
        </section>
        <aside>
            <h2>รายการที่เลือก</h2>
            <ul id="order-list"></ul>
            <button onclick="submitOrder()">สั่งออเดอร์</button>
            <button onclick="window.location.href='winai/Homepage/html/payment.php'">ชำระเงิน</button>
        </aside>
    </main>
</body>
</html>
