<?php
include '../../../config/connect_db.php';

define('BASE_URL', 'http://localhost:8081/winai_shabu-main/');

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
        ORDER BY c.category_name";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $package_id);
$stmt->execute();
$result = $stmt->get_result();

// สร้างอาร์เรย์แยกตามหมวดหมู่ และเก็บหมวด 'อื่นๆ' แยกไว้ต่างหาก
$groupedMenu = [];
$otherCategory = []; // เก็บหมวดหมู่ "อื่นๆ" แยกออกไป
while ($row = $result->fetch_assoc()) {
    $category = $row['category_name'];
    if ($category === 'อื่นๆ') {
        $otherCategory[$category][] = $row; // แยกเก็บหมวด "อื่นๆ"
    } else {
        if (!isset($groupedMenu[$category])) {
            $groupedMenu[$category] = [];
        }
        $groupedMenu[$category][] = $row;
    }
}

// เพิ่มหมวด "อื่นๆ" ไว้ท้ายสุด
if (!empty($otherCategory)) {
    $groupedMenu = array_merge($groupedMenu, $otherCategory);
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>Winai's Shabu</title>
    <link rel="stylesheet" href="../../CSS/style.css">
    <script src="../../Javascript/scripts.js" defer></script>
</head>

<body>
    <main>
        <!-- 🔹 ส่วนแสดงปุ่มหมวดหมู่ -->
        <div id="category-buttons">
            <?php foreach ($groupedMenu as $category => $items): ?>
                <button class="category-btn" onclick="showCategory('<?php echo htmlspecialchars($category); ?>')">
                    <?php echo htmlspecialchars($category); ?>
                </button>
            <?php endforeach; ?>
        </div>

        <!-- 🔹 ส่วนแสดงเมนู แยกตามหมวดหมู่ -->
        <section id="menu-section">
            <?php foreach ($groupedMenu as $category => $items): ?>
                <div class="category-section" id="category-<?php echo htmlspecialchars($category); ?>" style="display: none;">
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
                </div>
            <?php endforeach; ?>
        </section>

        <aside>
            <h2>รายการที่เลือก</h2>
            <ul id="order-list"></ul>
            <button onclick="submitOrder('<?php echo $reservation_id; ?>')">สั่งออเดอร์</button>
            <button onclick="window.location.href='order_list.php?reservation_id=<?php echo $reservation_id; ?>'">ชำระเงิน</button>
        </aside>
    </main>

    <script src="../../Javascript/submitOrder.js"></script>
    <script>
        function showCategory(category) {
            // ซ่อนทุกหมวดหมู่ก่อน
            document.querySelectorAll('.category-section').forEach(section => {
                section.style.display = 'none';
            });

            // แสดงเฉพาะหมวดหมู่ที่เลือก
            document.getElementById('category-' + category).style.display = 'block';
        }

        // ตั้งค่าให้แสดงหมวดหมู่แรกเมื่อโหลดหน้าเว็บ
        document.addEventListener("DOMContentLoaded", function () {
            let firstCategory = document.querySelector('.category-section');
            if (firstCategory) {
                firstCategory.style.display = 'block';
            }
        });
    </script>
</body>

</html>
