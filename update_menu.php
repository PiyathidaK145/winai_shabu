<?php
// เชื่อมต่อฐานข้อมูล
$servername = "localhost";
$username = "root"; // เปลี่ยนตามการตั้งค่าของคุณ
$password = "123456"; // เปลี่ยนตามการตั้งค่าของคุณ
$dbname = "winaishabu"; // เปลี่ยนตามชื่อฐานข้อมูลของคุณ

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ดึงข้อมูลเมนูและ URL รูปภาพจากฐานข้อมูล
$sql = "SELECT item_name, image_url FROM raw_material";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // แสดงข้อมูล
    while($row = $result->fetch_assoc()) {
        echo "<h3>" . $row['item_name'] . "</h3>";
        echo "<img src='" . $row['image_url'] . "' alt='" . $row['item_name'] . "' style='width: 200px;'><br><br>";
    }
} else {
    echo "ไม่พบข้อมูล";
}

$conn->close();
?>
