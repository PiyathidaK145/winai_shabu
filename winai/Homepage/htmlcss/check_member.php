<?php
$host = "localhost";
$user = "root";
$password = "123456";
$database = "winaishabu";

// เชื่อมต่อฐานข้อมูล
$conn = new mysqli($host, $user, $password, $database);

// หากการเชื่อมต่อล้มเหลว
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ตั้งค่าให้ใช้ UTF-8
header('Content-Type: text/html; charset=utf-8');

// ตรวจสอบว่ามีการส่งข้อมูลมาใน POST หรือไม่
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับค่า first_name หรือ last_name จาก request
    if (isset($_POST['first_name']) && isset($_POST['last_name'])) {
        $first_name = trim($_POST['first_name']);  // ลบช่องว่างก่อนและหลัง
        $last_name = trim($_POST['last_name']);    // ลบช่องว่างก่อนและหลัง

        // ตรวจสอบว่าค่าที่ได้รับมีความถูกต้อง
        if (empty($first_name) || empty($last_name)) {
            echo "Error: ชื่อและนามสกุลไม่สามารถว่างได้!";
            exit;
        }

        // เตรียมคำสั่ง SQL เพื่อหาชื่อและนามสกุลที่ตรงกัน
        $stmt = $conn->prepare("SELECT * FROM `Member` WHERE first_name = ? AND last_name = ?");
        if ($stmt === false) {
            echo "Error preparing statement: " . $conn->error;
            exit;
        }

        // ผูกค่าพารามิเตอร์
        $stmt->bind_param("ss", $first_name, $last_name);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "exists"; // พบชื่อและนามสกุลในระบบ
        } else {
            echo "not_found"; // ไม่พบชื่อและนามสกุล
        }

        $stmt->close();
    } else {
        echo "Error: ข้อมูล 'first_name' หรือ 'last_name' ไม่ถูกส่งมา!";
    }
}

// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();
?>
