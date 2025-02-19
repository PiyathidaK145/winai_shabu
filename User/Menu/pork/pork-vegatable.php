<?php
// เริ่มต้นเซสชัน (ถ้าจำเป็นสำหรับการจัดการข้อมูลผู้ใช้ในอนาคต)
session_start();
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Winai's Shabu</title>
    <link rel="stylesheet" href="../CSS/menu.css">
    <!-- Preload Resource -->
    <link rel="preload" href="../img/meat/beef.jpg" as="image">
    <link rel="preload" href="../img/vegatable/vegatable.jpg" as="image">
    <link rel="preload" href="../img/seafood/seafood.jpg" as="image">
    <link rel="preload" href="../img/other/other.jpg" as="image">
    <script src="../Javascript/menu-set.js"></script>
    <script src="../Javascript/select_soup.js"></script>
    <link rel="stylesheet" href="../CSS/select_soup.css">
    <link rel="stylesheet" href="/CSS/price.css">
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
                    $soups = [
                        ['src' => '../img/Soups/น้ำซุปต้นตำรับ.jpg', 'name' => 'น้ำซุปน้ำใส'],
                        ['src' => '../img/Soups/น้ำซุปน้ำดำ.jpg', 'name' => 'ซุปน้ำดำญี่ปุ่น'],
                        ['src' => '../img/Soups/น้ำซุปหม่าล่า.jpg', 'name' => 'น้ำซุปหม่าล่า']
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
                    $items = [
                        ["src" => "../img/vegatable/ผักบุ้ง.webp", "name" => "ผักบุ้ง"],
                        ["src" => "../img/vegatable/ต้นหอม.jpg", "name" => "ต้นหอม"],
                        ["src" => "../img/vegatable/ข้าวโพดอ่อน.jpg", "name" => "ข้าวโพดอ่อน"],
                        ["src" => "../img/vegatable/กวางตุ้ง.jpg", "name" => "กวางตุ้ง"],
                        ["src" => "../img/vegatable/ผักขึ้นฉ่าย.jpg", "name" => "ผักขึ้นฉ่าย"],
                        ["src" => "../img/vegatable/ฮ่องเต้น้อย.jpg", "name" => "ฮ่องเต้น้อย"],
                        ["src" => "../img/vegatable/ฟักทองญี่ปุ่น.jpg", "name" => "ฟักทองญี่ปุ่น"],
                        ["src" => "../img/vegatable/สาหร่ายวากาเมะ.jpg", "name" => "วากาเมะเขียว"],
                        ["src" => "../img/vegatable/ผักกาดขาว.jpg", "name" => "ผักกาดขาว"],
                        ["src" => "../img/vegatable/เห็ดฟาง.webp", "name" => "เห็ดฟาง"],
                        ["src" => "../img/vegatable/เห็ดหูหนู.jpg", "name" => "เห็ดหูหนู"],
                        ["src" => "../img/vegatable/เห็ดเข็มทอง.webp", "name" => "เห็ดเข็มทอง"],
                        ["src" => "../img/vegatable/ข้าวโพด.webp", "name" => "ข้าวโพด"],
                        ["src" => "../img/vegatable/เห็ดชิเมจิขาว.jpg", "name" => "เห็ดชิเมจิขาว"]
                    ];

                    foreach ($items as $item) {
                        echo "<div class='image-item' data-category='Beefset'>";
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

        <?php include 'footer.php'; ?> <!-- เพิ่มไฟล์ Footer -->
    </div>
</body>

</html>

