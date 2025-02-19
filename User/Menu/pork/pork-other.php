<?php
// เริ่มต้นไฟล์ PHP
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>A's Shabu</title>
    <link rel="stylesheet" href="../CSS/menu.css">
    <!-- Preload Resource -->
    <link rel="preload" href="../img/meat/beef.jpg" as="image">
    <link rel="preload" href="../img/vegatable/vegatable.jpg" as="image">
    <link rel="preload" href="../img/seafood/seafood.jpg" as="image">
    <link rel="preload" href="../img/other/other.jpg" as="image">
    <script src="../Javascript/menu-set.js"></script>
    <script src="../Javascript/select_soup.js"></script>
    <link rel="stylesheet" href="../CSS/select_soup.css">
    <link rel="stylesheet" href="../CSS/price.css">
    <script src="../Javascript/submitOrder.js"></script>
    <script src="../Javascript/order_Summary.js"></script>
    <link rel="stylesheet" href="../CSS/order_Summary.css">

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
        <a href="../PHP/pork-pork.php" class="back-button">←</a>
            <div class="search-bar">
                <input type="text" placeholder="ค้นหา">
                <button>🔍</button>
            </div>
        </div>
        <main>
            <section id="menu-section">
                <div class="menu-carousel">
                    <?php
                    // เมนูซุป
                    $soups = [
                        ['src' => '../img/Soups/น้ำซุปต้นตำรับ.jpg', 'name' => 'น้ำซุปน้ำใส'],
                        ['src' => '../img/Soups/น้ำซุปน้ำดำ.jpg', 'name' => 'ซุปน้ำดำญี่ปุ่น'],
                        ['src' => '../img/Soups/น้ำซุปหม่าล่า.jpg', 'name' => 'น้ำซุปหม่าล่า']
                    ];

                    foreach ($soups as $soup) {
                        echo "<div class='menu-item' onclick=\"toggleSoupSelection(this, '{$soup['name']}')\">";
                        echo "<img src='{$soup['src']}'>";
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
                                ['href' => 'pork-menu.php', 'data' => 'pork', 'src' => '../img/meat/Pork.jpg', 'name' => 'หมู'],
                                ['href' => 'pork-processed.php', 'data' => 'processed', 'src' => '../img/processed/มาม่า.JPG', 'name' => 'ของแปรรูป'],
                                ['href' => 'pork-vegatable.php', 'data' => 'vegetables', 'src' => '../img/vegatable/vegatarian.jpg', 'name' => 'ผัก'],
                                ['href' => 'pork-fruit.php', 'data' => 'fruit', 'src' => '../img/Fruits/Fruit.JPG', 'name' => 'ผลไม้'],
                                ['href' => 'pork-other.php', 'data' => 'other', 'src' => '../img/other/other.jpg', 'name' => 'อื่นๆ']
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


            <section id="image-gallery" data-category="Porkset">
                <div class="image-grid">
                    <?php
                    // รายการเมนู
                    $items = [
                        ["src" => "../img/other/เผือกทอด.jpg", "name" => "เผือกทอด"],
                        ["src" => "../img/other/เฟรนช์ฟรายส์.jpg", "name" => "เฟรนช์ฟรายส์"],
                        ["src" => "../img/other/น้ำแร่.jpg", "name" => "น้ำแร่"],
                        ["src" => "../img/other/น้ำเก๊กฮวย.jpg", "name" => "น้ำเก๊กฮวย"],
                        ["src" => "../img/other/น้ำอัดลม.jpg", "name" => "น้ำอัดลม"],
                        ["src" => "../img/other/ชามะนาว.jpg", "name" => "ชามะนาว"],
                        ["src" => "../img/other/น้ำแตงโมปั่น.jpg", "name" => "น้ำแตงโมปั่น"],
                        ["src" => "../img/other/น้ำส้ม.jpg", "name" => "น้ำส้ม"],
                        ["src" => "../img/other/น้ำมะพร้าว.jpg", "name" => "น้ำมะพร้าว"],
                        ["src" => "../img/other/รวมมิตร.jpg", "name" => "รวมมิตร"],
                        ["src" => "../img/other/ทับทิมกรอบ.jpg", "name" => "ทับทิมกรอบ"],
                        ["src" => "../img/other/บัวลอยร้อน.jpg", "name" => "บัวลอย(ร้อน)"],
                        ["src" => "../img/other/บัวลอยน้ำขิง.jpg", "name" => "บัวลอยน้ำขิง"]
                    ];

                    foreach ($items as $item) {
                        echo "<div class='image-item'>";
                        echo "<img src='{$item['src']}' alt='{$item['name']}'>";
                        echo "<p>{$item['name']}</p>";
                        echo "<div class='menu-quantity'>";
                        echo "<button class='decrease' onclick=\"updateOrder('{$item['name']}', 0)\">-</button>";
                        echo "<span class='quantity' id='quantity-{$item['name']}' style='visibility: hidden;'>0</span>";
                        echo "<button class='increase' onclick=\"updateOrder('{$item['name']}', 0)\">+</button>";
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
