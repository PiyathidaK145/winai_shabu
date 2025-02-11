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
    <script src="../Javascript/menu-set.js"></script>
    <script src="../Javascript/select_soup.js"></script>
    <link rel="stylesheet" href="../CSS/select_soup.css">
    <script src="../Javascript/submitOrder.js"></script>
    <script src="../Javascript/order_Summary.js"></script>
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
            <a href="/วินัยชาบู/htmlcss/Homepage.php" class="back-button">&larr;</a>
            <div class="search-bar">
                <input type="text" placeholder="ค้นหา">
                <button>&#128269;</button>
            </div>
        </div>

        <main>
            <!-- เมนูซุป -->
            <section id="menu-section">
                <div class="menu-carousel">
                    <?php
                    $soups = [
                        ["src" => "../img/Soups/น้ำซุปต้นตำรับ.jpg", "name" => "น้ำซุปต้นตำรับ"],
                        ["src" => "../img/Soups/น้ำซุปน้ำดำ.jpg", "name" => "น้ำซุปน้ำดำญี่ปุ่น"],
                        ["src" => "../img/Soups/น้ำซุปกระดูกหมู.jpg", "name" => "น้ำซุปกระดูกหมู"],
                        ["src" => "../img/Soups/น้ำซุปหม่าล่า.jpg", "name" => "น้ำซุปหม่าล่า"],
                        ["src" => "../img/Soups/น้ำซุปต้มยำ.jpg", "name" => "น้ำซุปต้มยำ"]
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

            <!-- ตัวกรองประเภทอาหาร -->
            <section id="filter-section">
                <div class="filter-container">
                    <div id="category-filter">
                        <ul class="tabs">
                            <?php
                            $categories = [
                                ['href' => '#', 'data' => 'beef', 'src' => '../img/seafood/seafood.jpg', 'name' => 'ทะเล'],
                                ['href' => '#', 'data' => 'processed', 'src' => '../img/processed/มาม่า.JPG', 'name' => 'ของแปรรูป'],
                                ['href' => '#', 'data' => 'vegetables', 'src' => '../img/vegatable/vegatarian.jpg', 'name' => 'ผัก'],
                                ['href' => '#', 'data' => 'fruit', 'src' => '../img/Fruits/Fruit.JPG', 'name' => 'ผลไม้'],
                                ['href' => '#', 'data' => 'other', 'src' => '../img/other/other.jpg', 'name' => 'อื่นๆ']
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

            <!-- เมนูอาหารตามประเภท -->
            <section id="image-gallery" data-category="Beefset">
                <div class="image-grid">
                    <?php
                    $meats = [
                        ["src" => "../img/seafood/แมงกะพรุน.png", "name" => "แมงกะพรุน"],
                        ["src" => "../img/seafood/หอยเป่าฮื้อ.jpg", "name" => "หอยเป๋าฮื้อ 60g/1ตัว"],
                        ["src" => "../img/seafood/เนื้อปลาแพนกาเซีย.jpg", "name" => "เนื้อปลาแพนกาเซียสดอลลี่"],
                        ["src" => "../img/seafood/ปลาหมึก.png", "name" => "ปลาหมึกสด"],
                        ["src" => "../img/seafood/เนื้อกุ้งสด.jpg", "name" => "เนื้อกุ้งขาว"],
                        ["src" => "../img/seafood/หอยแมลงภู่นิวซีแลนด์.jpg", "name" => "หอยแมลงภู่นิวซีแลนด์"]
                    ];
                    $itemss = [
                        ["src" => "../img/All-menu/เส้นอุด้ง.jpg", "name" => "เส้นอุด้ง"],
                        ["src" => "../img/All-menu/ลูกชิ้นปิงปอง.jpg", "name" => "ลูกชิ้นปิงปอง"],
                        ["src" => "../img/All-menu/ลูกชิ้นรักบี้.jpg", "name" => "ลูกชิ้นรักบี้"],
                        ["src" => "../img/All-menu/เต้าหู้ม้วนห่อสาหร่าย.jpg", "name" => "เต้าหู้ม้วนห่อสาหร่าย"],
                        ["src" => "../img/All-menu/คริสตัลไข่ปลา.jpg", "name" => "คริสตัลไข่ปลา"],
                        ["src" => "../img/All-menu/ลูกชิ้นกุ้ง.jpg", "name" => "ลูกชิ้นกุ้ง"],
                        ["src" => "../img/All-menu/บะหมี่หยกไต้หวัน.jpg", "name" => "บะหมี่หยกไต้หวัน"]
                    ];
                    $itemsf = [
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
                    $items = [
                        ["src" => "../img/other/ซาลาเปาหมูแดง.jpg", "name" => "ซาลาเปาหมูแดง"],
                        ["src" => "../img/other/ซาลาเปาหมูสับ.jpg", "name" => "ซาลาเปาหมูสับ"],
                        ["src" => "../img/other/ซาลาเปาไส้ครีม.jpg", "name" => "ซาลาเปาไส้ครีม"],
                        ["src" => "../img/other/ซาลาเปาไส้ถั่วดำ.jpg", "name" => "ซาลาเปาไส้ถั่วครีม"],
                        ["src" => "../img/other/ขนมจีบหมู.jpg", "name" => "ขนมจีบหมู"],
                        ["src" => "../img/other/ฮะเก๋า.jpg", "name" => "ฮะเก๋า"],
                        ["src" => "../img/other/เผือกทอด.jpg", "name" => "เผือกทอด"],
                        ["src" => "../img/other/ปอเปี๊ยะทอด.jpg", "name" => "ปอเปี๊ยะทอด"],
                        ["src" => "../img/other/ขนมจีบกุ้ง.jpg", "name" => "ขนมจีบกุ้ง"],
                        ["src" => "../img/other/เผือกทอด.jpg", "name" => "เผือกทอด"],
                        ["src" => "../img/other/เฟรนช์ฟรายส์.jpg", "name" => "เฟรนช์ฟรายส์"],
                        ["src" => "../img/other/เสี่ยวหลงเปาไส้กุ้งและหมูสับ.jpg", "name" => "เสี่ยวหลงเปาไส้กุ้งและหมูสับ"],
                        ["src" => "../img/other/น้ำแร่.jpg", "name" => "น้ำแร่"],
                        ["src" => "../img/other/น้ำเก๊กฮวย.jpg", "name" => "น้ำเก๊กฮวย"],
                        ["src" => "../img/other/น้ำอัดลม.jpg", "name" => "น้ำอัดลม"],
                        ["src" => "../img/All-menu/ชาเขียวน้ำผึ้งมะนาว.jpg", "name" => "ชาเขียวรสน้ำผึ้งมะนาว"],
                        ["src" => "../img/All-menu/ชาเขียวรสแตงโม.png", "name" => "ชาเขียวรสแตงโม"],
                        ["src" => "../img/other/น้ำส้ม.jpg", "name" => "น้ำส้ม"],
                        ["src" => "../img/other/น้ำมะพร้าว.jpg", "name" => "น้ำมะพร้าว"],
                        ["src" => "../img/other/ชานมไข่มุก.png", "name" => "ชานมไข่มุก"],
                        ["src" => "../img/other/ทับทิมกรอบ.jpg", "name" => "ทับทิมกรอบ"],
                        ["src" => "../img/other/บัวลอยร้อน.jpg", "name" => "บัวลอย(ร้อน)"],
                        ["src" => "../img/other/บัวลอยน้ำขิง.jpg", "name" => "บัวลอยน้ำขิง"],
                        ["src" => "../img/other/บัวลอยจิ มันม่วง (ร้อน).jpg", "name" => "บัวลอยไส้มันม่วง"]
                    ];

                    foreach ($meats as $meat) {
                        echo "<div class='image-item'>";
                        echo "<img src='{$meat['src']}' alt='{$meat['name']}'>";
                        echo "<p>{$meat['name']}</p>";
                        echo "<div class='menu-quantity'>";
                        echo "<button class='decrease' onclick=\"updateOrder('{$meat['name']}', 0)\">-</button>";
                        echo "<span class='quantity' id='quantity-{$meat['name']}' style='visibility: hidden;'>0</span>";
                        echo "<button class='increase' onclick=\"updateOrder('{$meat['name']}', 0)\">+</button>";
                        echo "</div></div>";
                    }
                    foreach ($itemss as $item) {
                        echo "<div class='image-item' data-category='Beefset'>";
                        echo "<img src='{$item['src']}' alt='{$item['name']}'>";
                        echo "<p>{$item['name']}</p>";
                        echo "<div class='menu-quantity'>";
                        echo "<button class='decrease' onclick=\"updateOrder('{$item['name']}', 0)\">-</button>";
                        echo "<span class='quantity' id='quantity-{$item['name']}' style='visibility: hidden;'>0</span>";
                        echo "<button class='increase' onclick=\"updateOrder('{$item['name']}', 0)\">+</button>";
                        echo "</div></div>";
                    }
                    foreach ($itemsf as $item) {
                        echo "<div class='image-item' data-category='Beefset'>";
                        echo "<img src='{$item['src']}' alt='{$item['name']}'>";
                        echo "<p>{$item['name']}</p>";
                        echo "<div class='menu-quantity'>";
                        echo "<button class='decrease' onclick=\"updateOrder('{$item['name']}', 0)\">-</button>";
                        echo "<span class='quantity' id='quantity-{$item['name']}' style='visibility: hidden;'>0</span>";
                        echo "<button class='increase' onclick=\"updateOrder('{$item['name']}', 0)\">+</button>";
                        echo "</div></div>";
                    }
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