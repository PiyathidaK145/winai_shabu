<?php
// Start PHP code to manage dynamic functionality
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Winai's Shabu</title>
    <link rel="stylesheet" href="../CSS/menu.css">
    <script src="../Javascript/menu-set.js"></script>
    <script src="../Javascript/order.js"></script>
    <script src="../Javascript/select_soup.js"></script>
    <link rel="stylesheet" href="../CSS/select_soup.css">
    <link rel="stylesheet" href="../CSS/price.css">
    <script src="../Javascript/submitOrder.js"></script>
    <script src="../Javascript/order_Summary.js"></script>
    <link rel="stylesheet" href="../CSS/order_Summary.css">
    <!-- Preload Resource -->
    <link rel="preload" href="../img/meat/beef.jpg" as="image">
    <link rel="preload" href="../img/vegatable/vegatable.jpg" as="image">
    <link rel="preload" href="../img/seafood/seafood.jpg" as="image">
    <link rel="preload" href="../img/other/other.jpg" as="image">
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
            <a href="/วินัยชาบู/Homepage/htmlcss/Homepage.php" class="back-button">←</a>
            <div class="search-bar">
                    <input type="text"  placeholder="ค้นหา">
                    <button type="submit">🔍</button>
                
            </div>
        </div>
        <main>
            <section id="menu-section">
                <div class="menu-carousel">
                    <?php
                    $soups = [
                        ['src' => '../img/Soups/น้ำซุปต้นตำรับ.jpg', 'name' => 'น้ำซุปต้นตำรับ'],
                        ['src' => '../img/Soups/น้ำซุปน้ำดำ.jpg', 'name' => 'ซุปน้ำดำญี่ปุ่น'],
                        ['src' => '../img/Soups/น้ำซุปกระดูกหมู.jpg', 'name' => 'น้ำซุปกระดูกหมู'],
                        ['src' => '../img/Soups/น้ำซุปหม่าล่า.jpg', 'name' => 'น้ำซุปหม่าล่า'],
                        ['src' => '../img/Soups/น้ำซุปต้มยำ.jpg', 'name' => 'น้ำซุปต้มยำ']
                    ];

                    foreach ($soups as $soup) {
                        echo "<div class='menu-item' onclick=\"toggleSoupSelection(this, '{$soup['name']}')\">";
                        echo "<img src='{$soup['src']}' alt='{$soup['name']}'>";
                        echo "<p>{$soup['name']}</p>";
                        echo "</div>";
                    }
                    ?>
                </div>
                <div id="confirm-button-container">
                    <button id="confirm-button" onclick="confirmSelection()" disabled>ยืนยัน</button>
                </div>
            </section>

            <!-- Filter Section -->
            <section id="filter-section">
                <div class="filter-container">
                    <div id="category-filter">
                        <ul class="tabs">
                            <?php
                            $categories = [
                                ['href' => 'beef_menu.php', 'data' => 'beef', 'src' => '../img/meat/beef.jpg', 'name' => 'เนื้อ'],
                                ['href' => 'beef_processed.php', 'data' => 'processed', 'src' => '../img/processed/มาม่า.JPG', 'name' => 'ของแปรรูป'],
                                ['href' => 'beef_vegatable.php', 'data' => 'vegetables', 'src' => '../img/vegatable/vegatarian.jpg', 'name' => 'ผัก'],
                                ['href' => 'beef_fruit.php', 'data' => 'fruit', 'src' => '../img/Fruits/Fruit.JPG', 'name' => 'ผลไม้'],
                                ['href' => 'beef_other.php', 'data' => 'other', 'src' => '../img/other/other.jpg', 'name' => 'อื่นๆ']
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
                    <?php
                    $meats = [
                        ['src' => '../img/meat/เนื้อบริสเกต AUS สไลซ์.jpg', 'name' => 'เนื้อบริสเกต AUS สไลซ์'],
                        ['src' => '../img/meat/เนื้อนำเข้าสันคอวัว Australia สไลซ์.jpg', 'name' => 'เนื้อนำเข้าสันคอวัว Australia สไลซ์'],
                        ['src' => '../img/meat/เนื้อวัวอเมริกาส่วนพื้นท้องสไลด์.jpg', 'name' => 'เนื้อวัวอเมริกาส่วนพื้นท้องสไลด์'],
                        ['src' => '../img/meat/เนื้อพื้นท้องวัวออสเตรเลียสไลซ์แช่แข็ง.jpg', 'name' => 'เนื้อพื้นท้องวัวออสเตรเลียสไลซ์แช่แข็ง'],
                        ['src' => '../img/meat/A4.png', 'name' => 'เนื้อวากิวญี่ปุ่น A4'],
                        ['src' => '../img/meat/A5.png', 'name' => 'เนื้อวากิวญี่ปุ่น A5'],
                    ];

                    foreach ($meats as $meat) {
                        echo "<div class='image-item'>";
                        echo "<img src='{$meat['src']}' alt='{$meat['name']}'>";
                        echo "<p>{$meat['name']}</p>";
                        echo "<div class='menu-quantity'>";
                        echo "<button class='decrease' onclick=\"updateOrder('{$meat['name']}', 0)\">-</button>";
                        echo "<span class='quantity' id='quantity-{$meat['name']}' style='visibility: hidden;'>0</span>";
                        echo "<button class='increase' onclick=\"updateOrder('{$meat['name']}',0)\">+</button>";
                        echo "</div></div>";
                    }
                    ?>
                </div>
            </section>

            <aside id="order-summary">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <h2>รายการที่เลือก</h2>
                    <h2>จำนวน</h2>
                </div>
                <ul id="order-list">
                    <!-- รายการออเดอร์จะเพิ่มที่นี่ -->
                </ul>
                <button onclick="submitOrder()">สั่งออเดอร์</button>
                <button>ชำระเงิน</button>
            </aside>
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

