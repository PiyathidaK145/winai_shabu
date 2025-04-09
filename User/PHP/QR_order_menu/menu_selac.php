<?php
include '../../../config/connect_db.php';

mysqli_set_charset($conn, "utf8");
define('BASE_URL', 'http://localhost:8081/winai_shabu-main/');

if (!isset($_GET['getting_table_id'])) {
    die("\u274c ไม่พบรหัสการจองหรือ Walk-in");
}

$getting_table_id = intval($_GET['getting_table_id']);
$reservation_id = null;
$walkin_id = null;
$package_id = null;
$created_at = null;

// ตรวจสอบว่าข้อมูล getting_table มาจาก reservation หรือ walkin
$sql = "SELECT reservation_id, walkin_id,created_at FROM getting_table WHERE getting_table_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $getting_table_id);
$stmt->execute();
$stmt->bind_result($reservation_id, $walkin_id, $created_at);
$stmt->fetch();
$stmt->close();


// ตรวจสอบว่าใช่ reservation หรือ walkin
if ($reservation_id !== null) {
    // กรณีจองล่วงหน้า ใช้ reservation_id เพื่อหาข้อมูล package
    $sql = "SELECT package_id FROM getting_table WHERE reservation_id = ? AND getting_table_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $reservation_id, $getting_table_id);
} elseif ($walkin_id !== null) {
    // กรณี Walk-in ใช้ walkin_id เพื่อหาข้อมูล package
    $sql = "SELECT package_id FROM getting_table WHERE walkin_id = ? AND getting_table_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $walkin_id, $getting_table_id);
} else {
    die("\u274c ไม่พบข้อมูล reservation หรือ walk-in สำหรับ Getting Table นี้");
}

$stmt->execute();
$stmt->bind_result($package_id);
$stmt->fetch();  // ตรวจสอบว่าได้ package_id หรือไม่
$stmt->close();


// หากไม่ได้พบ package_id
if ($package_id === null) {
    die("\u274c ไม่พบข้อมูล package");
}

// ตรวจสอบข้อมูลจาก reservation หรือ walkin เพื่อดึงข้อมูลลูกค้าและแพ็คเกจ
if ($reservation_id !== null) {
    $sql = "
        SELECT 
            c.first_name,
            c.last_name,
            ta.table_id,
            p.package_name
        FROM reservation r
        JOIN custumer c ON r.custumer_id = c.custumer_id
        JOIN getting_table gt ON r.reservation_id = gt.reservation_id
        JOIN table_availability ta ON r.availability_id = ta.availability_id
        JOIN package p ON gt.package_id = p.package_id
        WHERE r.reservation_id = ?
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $reservation_id);
} elseif ($walkin_id !== null) {
    $sql = "
        SELECT 
            c.first_name,
            c.last_name,
            ta.table_id,
            p.package_name
        FROM walkin w
        JOIN custumer c ON w.custumer_id = c.custumer_id
        JOIN getting_table gt ON w.walkin_id = gt.walkin_id
        JOIN table_availability ta ON w.availability_id = ta.availability_id
        JOIN package p ON gt.package_id = p.package_id
        WHERE w.walkin_id = ?
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $walkin_id);
} else {
    die("\u274c ไม่พบข้อมูลการจองหรือ Walk-in สำหรับ Getting Table นี้");
}

// รันคำสั่ง SQL
$stmt->execute();

// ผูกค่ากับตัวแปร
$stmt->bind_result($first_name, $last_name, $table_id, $package_name);

if ($stmt->fetch()) {
    // เก็บข้อมูลในตัวแปร
    $customer_name = $first_name . ' ' . $last_name;
    $table_number = $table_id;
    $package_name = $package_name;
} else {
    // หากไม่พบข้อมูล
    $customer_name = "ไม่พบข้อมูล";
    $table_number = "ไม่พบข้อมูล";
    $package_name = "ไม่พบข้อมูล";
}
$stmt->execute();
$stmt->close();
// ขั้นตอน 1: ดึง promotion_id จาก getting_table
$sql_promotion = "SELECT promotion_id FROM getting_table WHERE getting_table_id = ?";
$stmt_promotion = $conn->prepare($sql_promotion);
$stmt_promotion->bind_param("i", $getting_table_id);  // binding with your variable
$stmt_promotion->execute();
$result_promotion = $stmt_promotion->get_result();

// ตรวจสอบว่ามี promotion_id หรือไม่
if ($result_promotion->num_rows == 0) {
    die("ไม่พบข้อมูลโปรโมชั่น");
}

$row_promotion = $result_promotion->fetch_assoc();
$promotion_id = $row_promotion['promotion_id'];  // ดึง promotion_id จาก getting_table
$stmt_promotion->close(); // ปิด stmt_promotion หลังจากใช้งานเสร็จ

// ขั้นตอน 2: ใช้ promotion_id ดึงข้อมูลเมนูจาก promotion_item
$sql_menu = "
    SELECT rm.raw_material_id, rm.item_name, rm.image_url, p.promotions_name 
    FROM promotion_item pi
    JOIN menu mi ON pi.menu_id = mi.menu_id
    JOIN raw_material rm ON mi.raw_material_id = rm.raw_material_id
    JOIN promotion p ON pi.promotion_id = p.promotion_id
    WHERE pi.promotion_id = ?
    ORDER BY p.promotion_id, rm.item_name, rm.image_url, p.promotions_name
";

$stmt_menu = $conn->prepare($sql_menu);

// ตรวจสอบว่า prepare สำเร็จหรือไม่
if ($stmt_menu === false) {
    die('Error preparing statement: ' . $conn->error);
}

$stmt_menu->bind_param("i", $promotion_id);  // ใช้ $promotion_id ที่ถูกต้อง

// ตรวจสอบว่า execute สำเร็จหรือไม่
if (!$stmt_menu->execute()) {
    die('Error executing statement: ' . $stmt_menu->error);
}

$result_menu = $stmt_menu->get_result();

// ตรวจสอบว่า result ได้รับค่าหรือไม่
if ($result_menu === false) {
    die('Error getting result: ' . $stmt_menu->error);
}

// จัดกลุ่มข้อมูลเมนูตามชื่อโปรโมชั่น
$groupedPromotion = [];
while ($row = $result_menu->fetch_assoc()) {
    $promotions_name = $row['promotions_name'];  // ชื่อโปรโมชั่น
    $item_name = $row['item_name'];  // ชื่อเมนู
    $raw_material_id = $row['raw_material_id'];  // รหัสวัสดุดิบ
    $image_url = $row['image_url'];  // URL ของภาพเมนู

    // ตรวจสอบว่าในแต่ละโปรโมชั่นมีการกรุ๊ปเมนูหรือยัง
    if (!isset($groupedPromotion[$promotions_name])) {
        $groupedPromotion[$promotions_name] = [];  // ถ้ายังไม่มี ให้สร้าง array ใหม่
    }

    // เพิ่มเมนูในแต่ละโปรโมชั่น
    $groupedPromotion[$promotions_name][] = [
        'raw_material_id' => $raw_material_id,
        'item_name' => $item_name,
        'image_url' => $image_url
    ];
}

// ปิด stmt_menu หลังจากใช้งานเสร็จ
$stmt_menu->close();

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

$groupedMenu = [];
$otherCategory = [];
while ($row = $result->fetch_assoc()) {
    $category = $row['category_name'];
    if ($category === 'อื่นๆ') {
        $otherCategory[$category][] = $row;
    } else {
        if (!isset($groupedMenu[$category])) {
            $groupedMenu[$category] = [];
        }
        $groupedMenu[$category][] = $row;
    }
}
if (!empty($otherCategory)) {
    $groupedMenu = array_merge($groupedMenu, $otherCategory);
}

$stmt->close();
$conn->close();

// แปลงเป็นวัตถุ DateTime
$created_datetime = new DateTime($created_at);

// บวกเวลาเพิ่ม 2 ชั่วโมง
$created_datetime->modify('+2 hours');

// แปลงเป็นสตริงในรูปแบบที่ต้องการ
$expiration_time = $created_datetime->format('Y-m-d H:i:s');

// แสดงผลลัพธ์
echo "<h3>เวลาที่รับโต๊ะ(บวก 2 ชั่วโมง): " . $created_at . "</h3>";
echo "<h3>หมดเวลาทาน : " . $expiration_time . "</h3>";
?>


<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>Winai's Shabu</title>
    <link rel="stylesheet" href="../../CSS/style.css">
    <script src="../../Javascript/scripts.js" defer>
    </script>

</head>

<body>
    <main>
        <h2>รายการเมนูในแพ็คเกจ</h2>
        <aside>
            <p>ชื่อ: <span id="customer-name"><?php echo htmlspecialchars($first_name); ?></span></p>
            <p>นามสกุล: <span id="customer-surname"><?php echo htmlspecialchars($last_name); ?></span></p>
            <p>โต๊ะ: <span id="table-number"><?php echo htmlspecialchars($table_id); ?></span></p>
            <p>แพ็คเกจ: <span id="package-name"><?php echo htmlspecialchars($package_name); ?></span></p>
            <p>จับเวลา 2 ชั่วโมง:</p>
            <p id="countdown-timer"></p>

            <script>
                // กำหนดระยะเวลา 2 ชั่วโมง (7200000 มิลลิวินาที)
                const countdownDuration = 2 * 60 * 60 * 1000; // 2 ชั่วโมง

                // เวลาสิ้นสุด = เวลาปัจจุบัน + 2 ชั่วโมง
                const endTime = new Date().getTime() + countdownDuration;

                function updateTimer() {
                    const now = new Date().getTime();
                    const remaining = endTime - now;

                    if (remaining <= 0) {
                        document.getElementById('countdown-timer').innerHTML = "หมดเวลา";
                    } else {
                        const hours = Math.floor(remaining / (1000 * 60 * 60));
                        const minutes = Math.floor((remaining % (1000 * 60 * 60)) / (1000 * 60));
                        const seconds = Math.floor((remaining % (1000 * 60)) / 1000);

                        document.getElementById('countdown-timer').innerHTML =
                            `${hours} ชั่วโมง ${minutes} นาที ${seconds} วินาที`;

                        setTimeout(updateTimer, 1000); // อัปเดตทุก 1 วินาที
                    }
                }

                updateTimer()
            </script>
        <div id="category-buttons">
            <?php foreach ($groupedMenu as $category => $items): ?>
                <button class="category-btn" onclick="showCategory('<?php echo htmlspecialchars($category); ?>')">
                    <?php echo htmlspecialchars($category); ?>
                </button>
            <?php endforeach; ?>
            <?php foreach ($groupedPromotion as $promotion => $items): ?>
                <button class="category-btn" onclick="showCategory('<?php echo htmlspecialchars($promotion); ?>')">
                    <?php echo htmlspecialchars($promotion); ?>
                </button>
            <?php endforeach; ?>
        </div>

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

            <?php foreach ($groupedPromotion as $promotion_name => $items): ?>
            <div class="category-section" id="category-<?php echo htmlspecialchars($promotion_name); ?>" style="display: none;">
                <h3><?php echo htmlspecialchars($promotion_name); ?></h3>
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
            ***เมื่อกดที่ปุ่มชำระเงินจะสามารถดูสถานะอาหารที่กดสั่งไปได้
            <ul id="order-list"></ul>
            <button onclick="submitOrder('<?php echo $getting_table_id; ?>')">สั่งออเดอร์</button>
            <button onclick="window.location.href='order_list.php?getting_table_id=<?php echo $getting_table_id; ?>'">ชำระเงิน</button>
        </aside>
    </main>

    <script src="../../Javascript/submitOrder.js"></script>
    <script>
        function showCategory(category) {
            document.querySelectorAll('.category-section').forEach(section => {
                section.style.display = 'none';
            });
            document.getElementById('category-' + category).style.display = 'block';
        }

        document.addEventListener("DOMContentLoaded", function() {
            let firstCategory = document.querySelector('.category-section');
            if (firstCategory) {
                firstCategory.style.display = 'block';
            }
        });
    </script>
</body>

</html>