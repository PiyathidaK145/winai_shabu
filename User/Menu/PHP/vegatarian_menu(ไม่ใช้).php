<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Winai's Shabu</title>
    <link rel="stylesheet" href="/CSS/menu.css">
    <!-- Preload Resource -->
    <link rel="preload" href="/img/meat/beef.jpg" as="image">
    <link rel="preload" href="/img/vegatable/vegatable.jpg" as="image">
    <link rel="preload" href="/img/seafood/seafood.jpg" as="image">
    <link rel="preload" href="/img/other/other.jpg" as="image">
    <script src="/Javascript/menu-set.js"></script>
    <script src="/Javascript/select_soup.js"></script>
    <link rel="stylesheet" href="/CSS/select_soup.css">
    <link rel="stylesheet" href="/CSS/price.css">
    <script src="/Javascript/submitOrder.js"></script>
    <script src="/Javascript/order_Summary.js"></script>
    <link rel="stylesheet" href="/CSS/order_Summary.css">
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
                        ["img" => "/menu/img/Soups/น้ำซุปต้นตำรับ.jpg", "name" => "น้ำซุปต้นตำรับ"],
                        ["img" => "/menu/img/Soups/น้ำซุปน้ำดำ.jpg", "name" => "น้ำซุปน้ำดำญี่ปุ่น"],
                        ["img" => "/menu/img/Soups/น้ำซุปกระดูกหมู.jpg", "name" => "น้ำซุปกระดูกหมู"],
                        ["img" => "/menu/img/Soups/น้ำซุปหม่าล่า.jpg", "name" => "น้ำซุปหม่าล่า"],
                        ["img" => "/menu/img/Soups/น้ำซุปต้มยำ.jpg", "name" => "น้ำซุปต้มยำ"]
                    ];
                    foreach ($soups as $soup) {
                        echo '<div class="menu-item" onclick="toggleSoupSelection(this, \'' . $soup['name'] . '\')">
                                <img src="' . $soup['img'] . '">
                                <p>' . $soup['name'] . '</p>
                            </div>';
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

            
            <section id="image-gallery" data-category="Beefset">
                <div class="image-grid">
                    <?php
                    $menu_items = [
                        ["img" => "ผักบุ้ง.webp", "name" => "ผักบุ้ง"],
                        ["img" => "ต้นหอม.jpg", "name" => "ต้นหอม"],
                        ["img" => "ข้าวโพดอ่อน.jpg", "name" => "ข้าวโพดอ่อน"],
                        ["img" => "กวางตุ้ง.jpg", "name" => "กวางตุ้ง"],
                        ["img" => "ผักขึ้นฉ่าย.jpg", "name" => "ผักขึ้นฉ่าย"],
                        ["img" => "ฮ่องเต้น้อย.jpg", "name" => "ฮ่องเต้น้อย"],
                        ["img" => "ฟักทองญี่ปุ่น.jpg", "name" => "ฟักทองญี่ปุ่น"],
                        ["img" => "สาหร่ายวากาเมะ.jpg", "name" => "วากาเมะเขียว"],
                        ["img" => "ผักกาดขาว.jpg", "name" => "ผักกาดขาว"],
                        ["img" => "เห็ดฟาง.webp", "name" => "เห็ดฟาง"],
                        ["img" => "เห็ดหูหนู.jpg", "name" => "เห็ดหูหนู"],
                        ["img" => "เห็ดเข็มทอง.webp", "name" => "เห็ดเข็มทอง"],
                        ["img" => "ข้าวโพด.webp", "name" => "ข้าวโพด"],
                        ["img" => "เห็ดชิเมจิขาว.jpg", "name" => "เห็ดชิเมจิขาว"],
                    ];
                    foreach ($menu_items as $item) {
                        echo "<div class='image-item'>
                                <img src='../Homepage/menu/img/vegatable/{$item['img']}' alt='{$item['name']}'>
                                <p>{$item['name']}</p>
                                <div class='menu-quantity'>
                                    <button class='decrease' onclick=\"updateOrder('{$item['name']}', -1)\">-</button>
                                    <span class='quantity' id='quantity-{$item['name']}' style='visibility: hidden;'>0</span>
                                    <button class='increase' onclick=\"updateOrder('{$item['name']}', 1)\">+</button>
                                </div>
                              </div>";
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
