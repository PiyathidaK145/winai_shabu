<?php
// Start PHP code to manage dynamic functionality
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>A's Shabu</title>
    <link rel="stylesheet" href="CSSmenu/menu.css">
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
            <h1>A's Shabu</h1>
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
            </section>

            <!-- Filter Section -->
            <section id="filter-section">
                <div class="filter-container">
                    <div id="category-filter">
                        <ul class="tabs">
                            <?php
                            $categories = [
                                ['href' => 'pork-menu.php', 'data' => 'beef', 'src' => '../img/meat/Pork.jpg', 'name' => 'เซตหมู'],
                                ['href' => 'meat-menu.php', 'data' => 'processed', 'src' => '../img/meat/beef.jpg', 'name' => 'เซตเนื้อ'],
                                ['href' => 'seafood-menu.php', 'data' => 'vegetables', 'src' => '../img/seafood/seafood.jpg', 'name' => 'เซตทะเล'],
                                ['href' => 'vegatarian-menu.php', 'data' => 'fruit', 'src' => '../img/vegatable/vegatarian.jpg', 'name' => 'มังสวิรัติ'],
                                ['href' => 'mix-menu.php', 'data' => 'other', 'src' => '../img/meat/Mix.jpg', 'name' => 'Mix']
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
                        ['src' => '../img/meat/คุโรบูตะสไลด์.png', 'name' => 'คุโรบูตะสไลด์'],
                        ['src' => '../img/meat/สามชั้นสไลด์เมกา.png', 'name' => 'สามชั้นสไลด์เมกา'],
                        ['src' => '../img/meat/หมูทรงเครื่อง.jpg', 'name' => 'หมูทรงเครื่อง'],
                        ['src' => '../img/meat/เนื้อหมู 4.jpg', 'name' => 'สามชั้นสไลด์'],
                        ['src' => '../img/meat/เนื้อหมู 5.jpg', 'name' => 'หมูพริกไทยดำ'],
                        ['src' => '../img/meat/สันคอหมูสไลด์.png', 'name' => 'สันคอหมูสไลด์'],
                        ['src' => '../img/meat/หมูนุ่มโรยงา.png', 'name' => 'หมูนุ่มโรยงา']
                    ];

                    foreach ($meats as $meat) {
                        echo "<div class='image-item'>";
                        echo "<img src='{$meat['src']}' alt='{$meat['name']}'>";
                        echo "<p>{$meat['name']}</p>";
                        echo "</div>";
                    }
                    ?>
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
