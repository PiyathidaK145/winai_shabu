<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>A's Shabu</title>
    <link rel="stylesheet" href="stylesHomepage.css">
    <!-- Preload Resource -->
    <link rel="preload" href="/img/meat/beef.jpg" as="image">
    <link rel="preload" href="/img/vegatable/vegatable.jpg" as="image">
    <link rel="preload" href="/img/seafood/seafood.jpg" as="image">
    <link rel="preload" href="/img/other/other.jpg" as="image">
</head>

<body>
    <div class="container">
        <header>
            <h1>A's Shabu</h1>
        </header>
        <div class="promotion-banner">
            <p>โปรโมชันพิเศษสำหรับเดือนนี้! ลดราคาสุดคุ้มทุกวันจันทร์-พฤหัสบดี</p>
        </div>
        <main>
            <section id="menu-section">
                <div class="menu-carousel">
                    <div class="menu-item">
                        <a href="pork-menu.php">
                            <img src="../img/meat/Pork.jpg" alt="หมู">
                            <p>หมู</p>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a href="seafood-menu.php">
                            <img src="../img/seafood/seafood.jpg" alt="ทะเล">
                            <p>ทะเล</p>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a href="meat-menu.php">
                            <img src="../img/meat/beef.jpg" alt="เนื้อ">
                            <p>เนื้อ</p>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a href="vegatarian-menu.php">
                            <img src="../img/vegatable/vegatarian.jpg" alt="มังสวิรัติ">
                            <p>มังสวิรัติ</p>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a href="mix-menu.php">
                            <img src="../img/meat/Mix.jpg" alt="Mix">
                            <p>Mix</p>
                        </a>
                    </div>
                    <?php
                    /*$menu_items = [
                        ['link' => 'pork-menu.php', 'img' => '../img/meat/Pork.jpg', 'alt' => 'หมู', 'name' => 'หมู'],
                        ['link' => 'meat-menu.php', 'img' => '../img/meat/beef.jpg', 'alt' => 'เนื้อ', 'name' => 'เนื้อ'],
                        ['link' => 'seafood-menu.php', 'img' => '../img/seafood/seafood.jpg', 'alt' => 'ทะเล', 'name' => 'ทะเล'],
                        ['link' => 'vegatarian-menu.php', 'img' => '../img/vegatable/vegatarian.jpg', 'alt' => 'มังสวิรัติ', 'name' => 'มังสวิรัติ'],
                        ['link' => 'mix-menu.php', 'img' => '../img/meat/Mix.jpg', 'alt' => 'Mix', 'name' => 'Mix'],
                    ];

                    foreach ($menu_items as $item) {
                        echo '<div class="menu-item">';
                        echo "<a href=\"{$item['link']}\">";
                        echo "<img src=\"{$item['img']}\" alt=\"{$item['alt']}\">";
                        echo "<p>{$item['name']}</p>";
                        echo '</a>';
                        echo '</div>';
                    }*/
                    ?>
                </div>
            </section>

            <section id="map">
                <h2>แผนผังร้าน</h2>
                <?php
                $servername = "localhost";
                $username = "root"; // เปลี่ยนเป็นชื่อผู้ใช้ของคุณ
                $password = "123456"; // เปลี่ยนเป็นรหัสผ่านของคุณ
                $dbname = "winaishabu"; // เปลี่ยนเป็นชื่อฐานข้อมูลของคุณ
                
                // เชื่อมต่อกับฐานข้อมูล
                $conn = new mysqli($servername, $username, $password, $dbname);
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // ดึงข้อมูลการจองทั้งหมดที่มีสถานะเป็น 'Comfirm'
                $sql = "SELECT availability_id FROM reservation WHERE status = 'Comfirm'";
                $result = $conn->query($sql);

                $reservedTables = []; // เก็บจำนวนครั้งที่โต๊ะถูกจอง
                
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        // คำนวณหมายเลขโต๊ะจาก availability_id
                        $tableId = floor($row['availability_id'] / 100); // สมมติว่า availability_id มีรูปแบบ 1022 -> โต๊ะ 10
                        if (!isset($reservedTables[$tableId])) {
                            $reservedTables[$tableId] = 0;
                        }
                        $reservedTables[$tableId]++;
                    }
                }
                ?>

                <section id="rectangle-under-map">
                    <div class="rectangle-box">
                        <?php
                        for ($i = 1; $i <= 20; $i++) {
                            echo "<div class=\"table table$i\" id=\"table-$i\" onclick=\"handleTableClick($i)\">$i</div>";
                        }
                        ?>
                        <div class="rectangle">ครัว</div>
                        <div class="rectangle1">ครัว</div>
                        <div class="rectangle2">แคชเชียร์</div>
                        <div class="rectangle3">ประตู</div>
                        <div class="rectangle4"></div>
                        <div class="rectangle4-text">สามารถจองได้</div>
                        <div class="rectangle5"></div>
                        <div class="rectangle5-text">ไม่สามารถจองได้</div>
                    </div>
                </section>
            </section>
        </main>

        <footer>
            <p>ติดต่อเรา: <a href="tel:0123456789">012-345-6789</a> | <a
                    href="https://facebook.com/example">Facebook</a> | <a href="https://maps.google.com">แผนที่ร้าน</a>
            </p>
        </footer>
    </div>
</body>
<script src="scriptHomepage.js"></script>



</html>