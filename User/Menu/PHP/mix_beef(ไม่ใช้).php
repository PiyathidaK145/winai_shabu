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

            <!-- Filter Section -->
            <section id="filter-section">
                <div class="filter-container">
                    <div id="category-filter">
                        <ul class="tabs">
                            <?php
                            $categories = [
                                ['href' => 'mix_beef.php', 'data' => 'beef', 'src' => '../img/meat/beef.jpg', 'name' => 'เนื้อ,หมู,ทะเล'],
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
                        ['src' => '../img/meat/บริสเก็ตออส.png', 'name' => 'เนื้อบริสเก็ต Australia'],
                        ['src' => '../img/meat/เนื้อวัว 2.jpg', 'name' => 'เนื้อ Australia สไลซ์'],
                        ['src' => '../img/meat/เนื้อวัว 1.jpg', 'name' => 'เนื้อ U.S สไลซ์'],
                        ['src' => '../img/meat/เนื้อสไลด์ออส.png', 'name' => 'ชุดเนื้อนำเข้า Australia สไลซ์'],
                        ['src' => '../img/meat/สามชั้นสไลด์เมกา.png', 'name' => 'ชุดเนื้อนำเข้า U.S สไลซ์'],
                        ['src' => '../img/meat/วากิวญี่ปุ่น.png', 'name' => 'บัลลังก์เนื้อวากิวญี่ปุ่น'],
                        ['src' => '../img/meat/เนื้อวัว 3.jpg', 'name' => 'บัลลังก์ A5 เนื้อวากิวญี่ปุ่นชัคโรล'],
                        ["src" => "../Homepage/menu/img/meat/หมูนุ่มโรยงา.png", "name" => "หมูนุ่มโรยงา"],
                        ["src" => "../Homepage/menu/img/meat/หมูพันเห็ด.png", "name" => "หมูชาบูพันเห็ดเข็มทอง"],
                        ["src" => "../Homepage/menu/img/meat/สันคอหมูสไลด์.png", "name" => "สันคอหมูสไลด์"],
                        ["src" => "../Homepage/menu/img/meat/คุโรบูตะสไลด์.png", "name" => "คุโรบูตะสไลด์"],
                        ["src" => "../Homepage/menu/img/meat/สามชั้นสไลด์เมกา.png", "name" => "สามชั้น US สไลด์"],
                        ["src" => "../Homepage/menu/img/meat/หมูทรงเครื่อง.jpg", "name" => "หมูทรงเครื่อง"]
                    ];

                    foreach ($meats as $meat) {
                        echo "<div class='image-item'>";
                        echo "<img src='{$meat['src']}' alt='{$meat['name']}'>";
                        echo "<p>{$meat['name']}</p>";
                        echo "<div class='menu-quantity'>";
                        echo "<button class='decrease' onclick=\"updateOrder('{$meat['name']}', -1)\">-</button>";
                        echo "<span class='quantity' id='quantity-{$meat['name']}' style='visibility: hidden;'>0</span>";
                        echo "<button class='increase' onclick=\"updateOrder('{$meat['name']}', 1)\">+</button>";
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

