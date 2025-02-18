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

$categories = ["vegatable/vegatable.jpg" => [
        'ผักบุ้ง', 'ต้นหอมญี่ปุ่น', 'ข้าวโพดอ่อน', 'กวางตุ้ง', 'ผักขึ้นฉ่าย', 
        'ฮ่องเต้น้อย', 'ฟักทองญี่ปุ่น', 'วากาเมะเขียว', 'ผัดกาดขาว', 'เห็ดฟาง', 
        'เห็ดหูหนู', 'เห็ดเข็มทอง', 'ข้าวโพด', 'เห็ดชิเมจิขาว'
    ],
    "processed/IMG_9996.JPG" => [
        'เส้นอุด้ง', 'ลูกชิ้นปิงปอง', 'ลูกชิ้นรักบี้', 'ฟองเต้าหู้ม้วนห่อสาหร่าย', 
        'คริสตัลไข่ปลา', 'ลูกชิ้นกุ้ง', 'บะหมี่หยกไต้หวัน'
    ],
    "other/IMG_0001.JPG" => [
        'ซาลาเปาหมูแดง', 'ซาลาเปาหมูสับ', 'ซาลาเปาไส้ครีม', 'ซาลาเปาไส้ถั่วดำ', 
        'ขนมจีบหมู', 'ฮะเก๋า', 'เผือกทอด', 'ปอเปี๊ยะทอด', 'ขนมจีบกุ้ง', 'ขนมจีบปู', 
        'เฟรนฟราย', 'เสี่ยวหลงเปาไส้หมู', 'น้ำแร่', 'น้ำเก๊กฮวย', 'น้ำอัดลม', 
        'ชาเขียวรสน้ำผึ้งมะนาว', 'น้ำชาเขียวรสแตงโม', 'น้ำส้ม', 'น้ำมะพร้าว', 
        'ชานมไข่มุก', 'ทับทิมกรอบมะพร้าวอ่อน', 'บัวลอย', 'บัวลอยน้ำขิง','บัวลอยไส้มันม่วง'
    ],
    "Fruits/IMG_9989.JPG" => [
        'แตงโม', 'แอปเปิ้ล', 'กล้วยน้ำว้า', 'กีวี่ เขียว', 'ทับทิม', 'สับปะรด', 
        'แคนตาลูป', 'แก้วมังกร', 'ส้ม'
    ],
    "meat/Mix.jpg" => [
        'หมูนุ่มโรยงา', 'สันคอหมูสไลซ์', 'สันคอคุโรบูตะสไลซ์', 'สามชั้นสไลซ์', 
        'หมูบะช่อทรงเครื่อง'
    ],
    "Soups/Soup.JPG" => [
        'น้ำซุปน้ำดำ', 'น้ำซุปหมาล่า', 'น้ำซุปน้ำใส'
    ],
    "Soups/S__7806992_upscayl_4x_realesrgan-x4plus.png" => [
        'น้ำจิ้มสุกกี้', 'น้ำจิ้มซีฟู๊ด', 'น้ำจิ้มงา', 'พริกยอดสนแดง', 
        'กระเทียม', 'มะนาว'
    ]]; // คงไว้ตามที่กำหนด

$sql = "SELECT mi.inventory_id, mi.item_name, mi.image_url FROM package_item pi 
JOIN inventory mi ON pi.inventory_id = mi.inventory_id WHERE pi.package_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $package_id);
$stmt->execute();
$result = $stmt->get_result();

$groupedMenu = [];
while ($row = $result->fetch_assoc()) {
    foreach ($categories as $image => $items) {
        if (in_array($row['item_name'], $items)) {
            $groupedMenu[$image][] = $row;
            break;
        }
    }
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
            <?php foreach ($groupedMenu as $categoryImage => $items): ?>
                <h3><img src="<?php echo BASE_URL . htmlspecialchars($categoryImage); ?>" width="100"></h3>
                <ul class="menu-list">
                    <?php foreach ($items as $item): ?>
                        <li class="menu-item">
                            <img src="<?php echo BASE_URL . htmlspecialchars($item['image_url']); ?>" width="100">
                            <p><?php echo htmlspecialchars($item['item_name']); ?></p>
                            <button onclick="updateOrder(<?php echo $item['inventory_id']; ?>, '<?php echo htmlspecialchars($item['item_name']); ?>', 1)">+</button>
                            <button onclick="updateOrder(<?php echo $item['inventory_id']; ?>, '<?php echo htmlspecialchars($item['item_name']); ?>', -1)">-</button>
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
