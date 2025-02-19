<?php
$host = "localhost";
$user = "root";
$password = "123456";
$database = "winaishabu";

// เชื่อมต่อฐานข้อมูล
$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("การเชื่อมต่อล้มเหลว: " . $conn->connect_error);
}

// ดึงข้อมูลเฉพาะเมนู "ผักบุ้ง"
$sql = "SELECT item_name, image_url FROM menu_item WHERE item_name = 'ผักบุ้ง'";
$result = $conn->query($sql);

// กำหนด BASE URL ให้เป็นโฟลเดอร์หลักของรูปภาพ
define('BASE_URL', 'http://localhost:8081/winai-customer-main-main/User/Menu/img/');

?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Winai's Shabu</title>
    <link rel="stylesheet" href="../CSS/menu.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Winai's Shabu</h1>
        </header>

        <main>
            <section id="menu-section">
                <div class="menu-carousel">
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <?php
                            // ตรวจสอบว่า image_url เป็น URL เต็มหรือไม่
                            if (!empty($row['image_url']) && filter_var($row['image_url'], FILTER_VALIDATE_URL)) {
                                $imagePath = $row['image_url']; // ใช้ URL จากฐานข้อมูล
                            } elseif (!empty($row['image_url'])) {
                                $imagePath = rtrim(BASE_URL, '/') . '/' . ltrim($row['image_url'], '/'); // ป้องกัน / ซ้ำซ้อน
                            } else {
                                $imagePath = rtrim(BASE_URL, '/') . '/default.jpg'; // ใช้รูปภาพเริ่มต้นถ้าไม่มีข้อมูล
                            }
                            ?>
                            <div class='menu-item'>
                                <img src="<?= htmlspecialchars($imagePath); ?>" alt="<?= htmlspecialchars($row['item_name']); ?>" 
                                     onerror="this.onerror=null;this.src='<?= rtrim(BASE_URL, '/') . '/default.jpg'; ?>'">
                                <p><?= htmlspecialchars($row['item_name']); ?></p>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p>ไม่มีเมนูที่พร้อมให้บริการ</p>
                    <?php endif; ?>
                </div>
            </section>
        </main>
    </div>
</body>
</html>

<?php $conn->close(); ?>
