<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "123456";
$database = "winaishabu";

// เชื่อมต่อฐานข้อมูล
$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("เชื่อมต่อฐานข้อมูลล้มเหลว: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);

    // แสดงค่าที่รับมาจากฟอร์ม
    echo "First Name: " . $first_name . "<br>";
    echo "Last Name: " . $last_name . "<br>";

    // ตรวจสอบในฐานข้อมูล
    // แก้ไขคำสั่ง SQL โดยใช้ backticks รอบชื่อของตาราง `member`
    $sql = "SELECT * FROM `member` WHERE first_name = ? AND last_name = ?";
    $stmt = $conn->prepare($sql);

    // ตรวจสอบว่า prepare ทำงานได้หรือไม่
    if (!$stmt) {
        die("เตรียมคำสั่ง SQL ล้มเหลว: " . $conn->error);
    }

    // ผูกตัวแปรกับคำสั่ง SQL
    $stmt->bind_param("ss", $first_name, $last_name);
    $stmt->execute();
    $result = $stmt->get_result();

    // แสดงผลลัพธ์ query
    echo "Number of rows: " . $result->num_rows . "<br>";

    if ($result->num_rows > 0) {
        echo "พบข้อมูลในฐานข้อมูล<br>";
        $_SESSION['first_name'] = $first_name;
        $_SESSION['last_name'] = $last_name;
        header("Location: Homepage.php");
        exit();
    } else {
        echo "ไม่พบข้อมูลในฐานข้อมูล<br>";
        header("Location: login_page.php?error=ชื่อหรือนามสกุลไม่ถูกต้อง");
        exit();
    }
}
?>
