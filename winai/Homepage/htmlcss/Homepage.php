<?php
// เชื่อมต่อกับฐานข้อมูล
$servername = "localhost";
$username = "root"; // เปลี่ยนเป็นชื่อผู้ใช้ของคุณ
$password = "123456"; // เปลี่ยนเป็นรหัสผ่านของคุณ
$dbname = "a_shabu"; // เปลี่ยนเป็นชื่อฐานข้อมูลของคุณ

// เชื่อมต่อฐานข้อมูล
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ดึงหมายเลขโต๊ะทั้งหมด (ปรับตามโครงสร้างฐานข้อมูล)
$sql_tables = "SELECT DISTINCT table_id FROM table_availability";
$result_tables = $conn->query($sql_tables);

$tables = [];

if ($result_tables->num_rows > 0) {
    while ($row = $result_tables->fetch_assoc()) {
        $table_id = $row['table_id'];

        // นับจำนวนเวลาที่จองแล้ว (Busy) สำหรับโต๊ะนี้
        $sql_check_full = "SELECT COUNT(*) as total_busy FROM table_availability WHERE table_id = $table_id AND status = 'Busy'";
        $result_check = $conn->query($sql_check_full);
        $row_check = $result_check->fetch_assoc();
        $total_busy = $row_check['total_busy'];

        // ดึงจำนวนเวลาทั้งหมดในระบบ
        $sql_total_times = "SELECT COUNT(*) as total_times FROM time_reserversion";
        $result_total_times = $conn->query($sql_total_times);
        $row_total_times = $result_total_times->fetch_assoc();
        $total_times = $row_total_times['total_times'];

        // ถ้าจองหมดทุกเวลา ให้เป็นสีเทาและสถานะ busy
        if ($total_busy >= $total_times) {
            $tables[$table_id] = ["color" => "gray", "status" => "busy"];
        } else {
            $tables[$table_id] = ["color" => "#ff5722", "status" => "available"];
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>A's Shabu</title>
    <link rel="stylesheet" href="stylesHomepage.css">
    <link rel="preload" href="../Uploads/.jpg" as="image">
    <link rel="preload" href="/img/vegatable/vegatable.jpg" as="image">
    <link rel="preload" href="/img/seafood/seafood.jpg" as="image">
    <link rel="preload" href="/img/other/other.jpg" as="image">
</head>

<body>
    <div class="container">
    <header>
            <h1>A's Shabu</h1>
            <div class="profile-icon">
                <a href="login_page.php">
                    <img src="../img/other/รูปโปรไฟล์.jpg" alt="image">
                </a>
            </div>
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
                </div>
            </section>

            <section id="map">
                <h2>แผนผังร้าน</h2>
                
                <section id="rectangle-under-map">
                    <div class="rectangle-box">
                        <?php
                        // จำนวนโต๊ะที่มีทั้งหมด (สมมติว่า 20 โต๊ะ)
                        for ($i = 1; $i <= 20; $i++) {
                            $color = $tables[$i]['color'];
                            $status = $tables[$i]['status'];
                            echo "<div class=\"table table$i\" id=\"table-$i\" data-status=\"$status\" style=\"background-color: $color;\">$i</div>";
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
            <p>ติดต่อเรา: <a href="tel:0123456789">012-345-6789</a> | <a href="https://facebook.com/example">Facebook</a> | <a href="https://maps.google.com">แผนที่ร้าน</a>
            </p>
        </footer>
    </div>
</body>
<script src="scriptHomepage.js"></script>
</html>

