<?php
include '../../../config/connect_db.php';

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
// คิวรีข้อมูลจากฐานข้อมูล
$sql = "
    SELECT DISTINCT p.image_url
    FROM promotion p
    JOIN promotion_item pi ON p.promotion_id = pi.promotion_id
    WHERE 
        p.image_url IS NOT NULL 
        AND p.image_url != ''
        AND pi.status = 'active'
";
$result = $conn->query($sql);

// สร้างอาร์เรย์เพื่อเก็บพาธของภาพ
$image_paths = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $image_url = trim($row['image_url']); // ลบช่องว่างที่อาจมีอยู่รอบ ๆ ค่าของ image_url
        if (!empty($image_url)) {
            $image_paths[] = '../../../client/Staff_Manager/' . htmlspecialchars($image_url);
        }
    }
} else {
    echo "ไม่มีข้อมูลโปรโมชั่นที่มีสถานะเป็น active";
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>A's Shabu</title>
    <link rel="stylesheet" href="../../CSS/stylesHomepage.css">
</head>


<body>
    <div class="container">
        <header>
            <h1>A's Shabu</h1>
            <div class="profile-icon">
                <a href="../login/login_page.php">
                    <img src="../../../img_profile/y7D4Fh0eTjm_gf4bkRDPxg.jpg" alt="image">
                </a>
            </div>
        </header>
        <div class="promotion-banner">
            <?php if (!empty($image_paths)) { ?>
                <div class="slideshow-container">
                    <?php foreach ($image_paths as $index => $image_path) { ?>
                        <div class="mySlides fade">
                            <img src="<?php echo $image_path; ?>" style="width:50%">
                        </div>
                    <?php } ?>
                </div>
            <?php } else { ?>
                <p>ไม่มีภาพโปรโมชั่น</p>
            <?php } ?>
            <p>โปรโมชันพิเศษสำหรับเดือนนี้! ลดราคาสุดคุ้มทุกวันจันทร์-พฤหัสบดี</p>
        </div>

        <main>
            <div class="menu-carousel">
                <div class="menu-item">
                    <a href="pork-menu.php">
                        <img src="../../../img_menu/หมู/สามชั้นสไลด์.png" alt="หมู">
                        <p>หมู</p>
                    </a>
                </div>
                <div class="menu-item">
                    <a href="seafood-menu.php">
                        <img src="../../../img_menu/อาหารทะเล/เนื้อกุ้งสด.jpg" alt="ทะเล">
                        <p>ทะเล</p>
                    </a>
                </div>
                <div class="menu-item">
                    <a href="meat-menu.php">
                        <img src="../../../img_menu/เนื้อ/A4.png" alt="เนื้อ">
                        <p>เนื้อ</p>
                    </a>
                </div>
                <div class="menu-item">
                    <a href="vegatarian-menu.php">
                        <img src="../../../img_menu/มังสวิรัติ.jfif" alt="มังสวิรัติ">
                        <p>มังสวิรัติ</p>
                    </a>
                </div>
                <div class="menu-item">
                    <a href="mix-menu.php">
                        <img src="../../../img_menu/mix.jfif" alt="Mix">
                        <p>Mix</p>
                    </a>
                </div>
            </div>
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
<script src="../../Javascript/scriptHomepage.js"></script>

</html>