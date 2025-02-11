<?php
// ฟังก์ชันที่จำเป็นสามารถใส่ไว้ในไฟล์อื่นแล้ว include เข้ามา
// เช่น include('functions.php');
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Winai's Shabu</title>
    <link rel="stylesheet" href="../CSS/menu.css">
    <script src="../Javascript/menu-set.js"></script>
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
            <a href="../htmlcss/หน้าแรก.html" class="back-button">←</a>
            <div class="search-bar">
                <input type="text" placeholder="ค้นหา">
                <button>🔍</button>
            </div>
        </div>
        <main>
            <section id="menu-section">
                <div class="menu-carousel">
                    <?php
                    $soups = [
                        ["name" => "ซุปต้นตำรับ", "image" => "../img/Soups/น้ำซุปต้นตำรับ.jpg"],
                        ["name" => "ซุปน้ำดำญี่ปุ่น", "image" => "../img/Soups/น้ำซุปน้ำดำ.jpg"],
                        ["name" => "น้ำซุปกระดูกหมู", "image" => "../img/Soups/น้ำซุปกระดูกหมู.jpg"],
                        ["name" => "น้ำซุปหม่าล่า", "image" => "../img/Soups/น้ำซุปหม่าล่า.jpg"]
                    ];

                    foreach ($soups as $soup) {
                        echo "<div class='menu-item' onclick=\"toggleSoupSelection(this, '{$soup['name']}')\">";
                        echo "<img src='{$soup['image']}'>";
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
                                ['href' => 'vegatarian_processed.php', 'data' => 'processed', 'src' => '../img/processed/มาม่า.JPG', 'name' => 'ของแปรรูป'],
                                ['href' => 'vegatarian_vegatable.php', 'data' => 'vegetables', 'src' => '../img/vegatable/vegatarian.jpg', 'name' => 'ผัก'],
                                ['href' => 'vegatarian_fruit.php', 'data' => 'fruit', 'src' => '../img/Fruits/Fruit.JPG', 'name' => 'ผลไม้'],
                                ['href' => 'vegatarian_other.php', 'data' => 'other', 'src' => '../img/other/other.jpg', 'name' => 'อื่นๆ']
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
                    $fruits = [
                        ["name" => "แตงโม", "image" => "../img/Fruits/watermelon.jpg"],
                        ["name" => "แอปเปิ้ล", "image" => "../img/Fruits/apple.jpg"],
                        ["name" => "กล้วย", "image" => "../img/Fruits/banana.jpg"],
                        ["name" => "กีวี่", "image" => "../img/Fruits/kiwi.jpg"],
                        ["name" => "ทับทิม", "image" => "../img/Fruits/Pomegranate.jpg"],
                        ["name" => "สัปปะรด", "image" => "../img/Fruits/pineapple.jpg"],
                        ["name" => "แคนตาลูป", "image" => "../img/Fruits/cantaloupe.jpg"],
                        ["name" => "แก้วมังกร", "image" => "../img/Fruits/dragon fruit.jpg"],
                        ["name" => "ส้ม", "image" => "../img/Fruits/orange.jpg"]
                    ];

                    foreach ($fruits as $fruit) {
                        echo "<div class='image-item'>";
                        echo "<img src='{$fruit['image']}'>";
                        echo "<p>{$fruit['name']}</p>";
                        echo "<div class='menu-quantity'>";
                        echo "<button class='decrease' onclick=\"updateOrder('{$fruit['name']}', 0)\">-</button>";
                        echo "<span class='quantity' id='quantity-{$fruit['name']}' style='visibility: hidden;'>0</span>";
                        echo "<button class='increase' onclick=\"updateOrder('{$fruit['name']}', 0)\">+</button>";
                        echo "</div>";
                        echo "</div>";
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
