<?php
$servername = "localhost";
$username = "root";
$password = "123456";
$dbname = "winaishabu";

// สร้างการเชื่อมต่อฐานข้อมูล
$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// รับค่าจากฟอร์มและป้องกัน SQL Injection
$first_name = $conn->real_escape_string($_POST['first_name']);
$number_of_guest = (int)$_POST['number_of_guest'];
$time_accept = $conn->real_escape_string($_POST['time_accept']);
$availability_id = (int)$_POST['availability_id'];
$status = "Confirm"; // ค่าเริ่มต้นเป็น "Comfirm"

// ตรวจสอบว่า availability_id ถูกต้องหรือไม่
if ($availability_id == 0) {
    die("Error: ไม่พบหมายเลขโต๊ะ กรุณาลองใหม่อีกครั้ง!");
}

// เตรียมคำสั่ง SQL สำหรับเพิ่มข้อมูล
$sql = "INSERT INTO Reservation (first_name, number_of_geust, status, time_accept, availability_id) 
        VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sissi", $first_name, $number_of_guest, $status, $time_accept, $availability_id);

// ดำเนินการคำสั่ง SQL
if ($stmt->execute()) {
    // เปลี่ยนเส้นทางไปยังหน้า Successfully.html หลังจากบันทึกสำเร็จ
    header("Location: Successfully.html");
    exit(); // หยุดการประมวลผลเพื่อป้องกันการรันโค้ดต่อไป
} else {
    echo "❌ เกิดข้อผิดพลาด: " . $stmt->error;
}

// ปิดการเชื่อมต่อ
$stmt->close();
$conn->close();
?>
