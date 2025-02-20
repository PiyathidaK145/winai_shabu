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

// ตรวจสอบว่ามีการส่งข้อมูลจากฟอร์มหรือไม่
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_names = $_POST['item_names']; // รับชื่อเมนูทั้งหมดจากฟอร์ม
    $images = $_FILES['images']; // รูปภาพที่อัปโหลด

    // ตรวจสอบว่าไฟล์ถูกอัปโหลดหรือไม่
    if (count($images['name']) > 0) {
        // ลูปผ่านไฟล์ทั้งหมดที่อัปโหลด
        foreach ($images['name'] as $index => $image_name) {
            if ($images['error'][$index] == 0) {
                // ตั้งค่าชื่อไฟล์ใหม่
                $upload_dir = "uploads/"; // โฟลเดอร์สำหรับเก็บไฟล์
                $file_name = $upload_dir . basename($image_name);
                
                // ย้ายไฟล์ไปยังโฟลเดอร์ที่กำหนด
                if (move_uploaded_file($images['tmp_name'][$index], $file_name)) {
                    // ใช้ชื่อไฟล์ที่อัปโหลดให้ตรงกับชื่อเมนูในฐานข้อมูล
                    $item_name = $item_names[$index]; // ดึงชื่อเมนูจาก array ของ item_names

                    // อัปเดต URL รูปภาพในฐานข้อมูล
                    $sql = "UPDATE raw_material SET image_url = ? WHERE item_name = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ss", $file_name, $item_name);

                    if ($stmt->execute()) {
                        echo "อัปโหลดและอัปเดตรูปภาพสำหรับเมนู " . $item_name . " สำเร็จ!<br>";
                    } else {
                        echo "เกิดข้อผิดพลาดในการอัปเดตรูปภาพ: " . $conn->error . "<br>";
                    }
                } else {
                    echo "การอัปโหลดไฟล์ " . $image_name . " ล้มเหลว.<br>";
                }
            } else {
                echo "เกิดข้อผิดพลาดในการอัปโหลดไฟล์: " . $image_name . "<br>";
            }
        }
    } else {
        echo "กรุณาเลือกไฟล์ที่ต้องการอัปโหลด.";
    }
}

$conn->close();
?>

<!-- ฟอร์ม HTML สำหรับอัปโหลดหลายไฟล์ -->
<form action="form.php" method="post" enctype="multipart/form-data">
    <!-- ชื่อเมนู -->
    <label for="item_names[]">ชื่อเมนู:</label><br>
    <input type="text" name="item_names[]" required><br>

    <!-- อัปโหลดหลายไฟล์ -->
    <label for="images[]">อัปโหลดรูปภาพ:</label>
    <input type="file" name="images[]" multiple required><br><br>

    <button type="submit">อัปโหลด</button>
</form>
