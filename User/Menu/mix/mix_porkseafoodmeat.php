<?php
// เริ่มต้นเซสชัน (ถ้าจำเป็นสำหรับการจัดการข้อมูลผู้ใช้ในอนาคต)
session_start();
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
            <a href="/วินัยชาบู/htmlcss/Homepage.php" class="back-button">&larr;</a>
            <div class="search-bar">
                <input type="text" placeholder="ค้นหา">
                <button>&#128269;</button>
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
                                ['href' => 'mix_porkseafoodmeat.php', 'data' => 'beef', 'src' => '../img/meat/Mix.jpg', 'name' => 'เนื้อ,หมู,ทะเล'],
                                ['href' => 'mix_processed.php', 'data' => 'processed', 'src' => '../img/processed/มาม่า.JPG', 'name' => 'ของแปรรูป'],
                                ['href' => 'mix_vegatable.php', 'data' => 'vegetables', 'src' => '../img/vegatable/vegatarian.jpg', 'name' => 'ผัก'],
                                ['href' => 'mix_fruit.php', 'data' => 'fruit', 'src' => '../img/Fruits/Fruit.JPG', 'name' => 'ผลไม้'],
                                ['href' => 'mix_other.php', 'data' => 'other', 'src' => '../img/other/other.jpg', 'name' => 'อื่นๆ']
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
                        ['src' => '../img/meat/เนื้อนำเข้าสันคอวัว Australia สไลซ์.jpg', 'name' => 'เนื้อนำเข้าสันคอวัว Australia สไลซ์'],
                        ['src' => '../img/meat/เนื้อวัวอเมริกาส่วนพื้นท้องสไลด์.jpg', 'name' => 'เนื้อวัวอเมริกาส่วนพื้นท้องสไลด์'],
                        ['src' => '../img/meat/เนื้อพื้นท้องวัวออสเตรเลียสไลซ์แช่แข็ง.jpg', 'name' => 'เนื้อพื้นท้องวัวออสเตรเลียสไลซ์แช่แข็ง'],
                        ['src' => '../img/meat/A4.png', 'name' => 'เนื้อวากิวญี่ปุ่น A4'],
                        ['src' => '../img/meat/A5.png', 'name' => 'เนื้อวากิวญี่ปุ่น A5'],
                        ['src' => '../img/meat/เนื้อบริสเกต AUS สไลซ์.jpg', 'name' => 'เนื้อบริสเกต AUS สไลซ์'],
                        ["src" => "../img/meat/หมูนุ่มโรยงา.png", "name" => "หมูนุ่มโรยงา"],
                        ["src" => "../img/meat/สันคอหมูสไลซ์.png", "name" => "สันคอหมูสไลซ์"],
                        ["src" => "../img/meat/สามชั้นสไลด์.png", "name" => "สามชั้นสไลซ์"],
                        ["src" => "../img/meat/สันคอคุโรบูตะสไลซ์.png", "name" => "สันคอคุโรบูตะสไลซ์"],
                        ["src" => "../img/meat/หมูบะช่อ.png", "name" => "หมูบะช่อทรงเครื่อง"],
                        ["src" => "../img/seafood/แมงกะพรุน.png", "name" => "แมงกะพรุน"],
                        ["src" => "../img/seafood/หอยเป่าฮื้อ.jpg", "name" => "หอยเป๋าฮื้อ 60g/1ตัว"],
                        ["src" => "../img/seafood/เนื้อปลาแพนกาเซีย.jpg", "name" => "เนื้อปลาแพนกาเซียสดอลลี่"],
                        ["src" => "../img/seafood/ปลาหมึก.png", "name" => "ปลาหมึกสด"],
                        ["src" => "../img/seafood/เนื้อกุ้งสด.jpg", "name" => "เนื้อกุ้งขาว"],
                        ["src" => "../img/seafood/หอยแมลงภู่นิวซีแลนด์.jpg", "name" => "หอยแมลงภู่นิวซีแลนด์"]
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

