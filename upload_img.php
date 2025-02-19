<?php
$host = "localhost";
$user = "root";
$password = "123456";
$database = "winaishabu";

// เชื่อมต่อฐานข้อมูล
$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("❌ การเชื่อมต่อล้มเหลว: " . $conn->connect_error);
}
mysqli_set_charset($conn, "utf8");


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $menu_name = $_POST["menu_name"]; // รับค่าชื่อเมนู
    $target_dir = "uploads/"; // โฟลเดอร์เก็บไฟล์
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]); // ไฟล์ปลายทาง

    // ตรวจสอบและอัปโหลดไฟล์
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        // บันทึกข้อมูลลงตาราง `raw_material`
        $sql = "INSERT INTO raw_material (name, image_path) VALUES ('$menu_name', '".basename($_FILES["fileToUpload"]["name"])."')";
        if ($conn->query($sql) === TRUE) {
            echo "อัปโหลดสำเร็จ! <a href='show_menu.php'>ดูเมนู</a>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "เกิดข้อผิดพลาดในการอัปโหลดไฟล์";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>อัปโหลดเมนู</title>
</head>
<body>
    <h2>อัปโหลดเมนู</h2>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        ชื่อเมนู: <input type="text" name="menu_name" required><br><br>
        เลือกรูปภาพ: <input type="file" name="fileToUpload" required><br><br>
        <input type="submit" value="อัปโหลด">
    </form>
</body>
</html>
