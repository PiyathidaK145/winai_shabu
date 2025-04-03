<?php
include '../../../config/connect_db.php';
define('BASE_URL', 'http://localhost:8081/winai_shabu-main/');

// ดึงข้อมูลแพ็คเกจมังสวิรัติ (package_id = 704)
$sql = "SELECT pi.menu_id, rm.item_name, rm.image_url 
        FROM package_item pi
        JOIN menu m ON pi.menu_id = m.menu_id
        JOIN raw_material rm ON m.raw_material_id = rm.raw_material_id
        WHERE pi.package_id = 704";
$result = $conn->query($sql);

$items = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }
}

// ดึงข้อมูลน้ำซุปจากฐานข้อมูล (category_id = 1106)
$sql = "SELECT item_name, image_url FROM raw_material WHERE category_id = 1106";
$result = $conn->query($sql);

$soups = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $soups[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>A's Shabu</title>
    <link rel="stylesheet" href="../../CSS/menu.css">
    <script src="../../Javascript/menu-set.js"></script>
    <script src="../../Javascript/select_soup.js"></script>
    <link rel="stylesheet" href="../../CSS/select_soup.css">
    <link rel="stylesheet" href="../../CSS/price.css">
    <script src="../../Javascript/submitOrder.js"></script>
    <script src="../../Javascript/order_Summary.js"></script>
    <link rel="stylesheet" href="../../CSS/order_Summary.css">
</head>

<body>
    <div class="container">
        <header>
            <h1>Winai's Shabu</h1>
        </header>
        <div class="promotion-banner">
            <p>โปรโมชันพิเศษสำหรับเดือนนี้! ลดราคาสุดคุ้มทุกวันจันทร์-พฤหัสบดี</p>
        </div>
        <div class="top-bar">
            <a href="Homepage.php" class="back-button">←</a>
            <div class="search-bar">
                <input type="text" placeholder="ค้นหา">
                <button type="submit">🔍</button>
            </div>
        </div>
        <main>
            <section id="menu-section">
                <div class="menu-carousel">
                    <?php foreach ($soups as $soup): ?>
                        <div class='menu-item' onclick="toggleSoupSelection(this, '<?php echo $soup['item_name']; ?>')">
                            <img src="<?php echo BASE_URL . $soup['image_url']; ?>" alt="<?php echo $soup['item_name']; ?>">
                            <p><?php echo $soup['item_name']; ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <!-- Filter Section -->
            <section id="filter-section">
                <div class="filter-container">
                    <div id="category-filter">
                        <ul class="tabs">
                            <?php
                            $categories = [
                                ['href' => 'pork-menu.php', 'data' => 'beef', 'src' => '../../../img_menu/หมู/สามชั้นสไลด์.png', 'name' => 'เซตหมู'],
                                ['href' => 'meat-menu.php', 'data' => 'processed', 'src' => '../../../img_menu/เนื้อ/A4.png', 'name' => 'เซตเนื้อ'],
                                ['href' => 'seafood-menu.php', 'data' => 'vegetables', 'src' => '../../../img_menu/อาหารทะเล/เนื้อกุ้งสด.jpg', 'name' => 'เซตทะเล'],
                                ['href' => 'vegatarian-menu.php', 'data' => 'fruit', 'src' => '../../../img_menu/มังสวิรัติ.jfif', 'name' => 'มังสวิรัติ'],
                                ['href' => 'mix-menu.php', 'data' => 'other', 'src' => '../../../img_menu/mix.jfif', 'name' => 'Mix']
                            ];

                            foreach ($categories as $category) {
                                echo "<li data-category='{$category['data']}'>";
                                echo "<a href='{$category['href']}'>";
                                echo "<img src='{$category['src']}' alt='{$category['name']}'>";
                                echo "<p>{$category['name']}</p>";
                                echo "</a></li>";
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </section>


            <section id="image-gallery">
                <div class="image-grid">
                    <?php foreach ($items as $item): ?>
                        <div class="image-item">
                            <img src="<?php echo BASE_URL . $item['image_url']; ?>" alt="<?php echo $item['item_name']; ?>">
                            <p><?php echo $item['item_name']; ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>



        </main>

        <!-- Footer -->
        <footer>
            <p>ติดต่อเรา:
                <a href="tel:0123456789">012-345-6789</a> |
                <a href="https://facebook.com/example">Facebook</a> |
                <a href="https://maps.google.com">แผนที่ร้าน</a>
            </p>
        </footer>
    </div>
</body>

</html>