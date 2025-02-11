<?php
$servername = "localhost";
$username = "root";
$password = "123456";
$dbname = "winaishabu";

$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ตรวจสอบว่ามีข้อมูลการจองใน session หรือไม่
    if (!isset($_SESSION['booking_data'])) {
        echo "<script>alert('ไม่มีข้อมูลการจอง!'); window.location.href='Homepage.php';</script>";
        exit();
    }

    // ดึงข้อมูลจาก session
    $booking = $_SESSION['booking_data'];

    // รับค่า availability_id
    $availability_id = $booking['availability_id']; // ✅ แก้ไขตรงนี้

    // ลบขั้นตอนการดึง member_id จากฐานข้อมูล
    // เนื่องจากไม่ต้องการใช้งาน member_id

    // ค่าคงที่ของ status เป็น 'Comfirm'
    $status = "Comfirm";
    $number_of_guest = isset($booking['number_of_guest']) ? $booking['number_of_guest'] : 0;
    $last_name = isset($booking['last_name']) ? $booking['last_name'] : '';

    // บันทึกข้อมูลลงในตาราง Reservation
    $stmt = $conn->prepare("INSERT INTO Reservation (first_name, last_name, status, time_update, availability_id, number_of_guest) VALUES (?, ?, ?, CURRENT_TIMESTAMP, ?, ?)");

    if (!$stmt) {
        die("Error preparing insert statement: " . $conn->error);
    }

    // กำหนดการ bind param โดยไม่ใช้ member_id
    $stmt->bind_param("sssii", $booking['first_name'], $last_name, $status, $availability_id, $number_of_guest);

    if ($stmt->execute()) {
        $reservation_id = $stmt->insert_id;
        header("Location: Successfully.php?reservation_id=$reservation_id&availability_id=$availability_id&table=" . $booking['table'] . "&time=" . $booking['time']);
        exit();
    } else {
        echo "เกิดข้อผิดพลาดในการบันทึกข้อมูล: " . $stmt->error;
        echo "<script>alert('เกิดข้อผิดพลาดในการจอง โปรดลองอีกครั้ง'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
