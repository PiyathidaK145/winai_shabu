<?php
$servername = "localhost";
$username = "root";
$password = "123456";
$dbname = "a_shabu";

$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// รับข้อมูลจาก JavaScript
$data = json_decode(file_get_contents('php://input'), true);

// ดึงข้อมูล
$get_table_id = $data['getting_table_id'];
$payment_method = $data['payment_method'];
$total_payment = $data['total_payment'];

// บันทึกข้อมูลลงฐานข้อมูล
$stmt = $conn->prepare("INSERT INTO payment (getting_table_id, payment_method, total_payment) VALUES (?, ?, ?)");
$stmt->bind_param("isi", $get_table_id, $payment_method, $total_payment);
$stmt->execute();

// ปิดการเชื่อมต่อ
$stmt->close();
$conn->close();

// ส่งข้อมูลไปที่ receipt.php
echo json_encode([
    'success' => true,
    'message' => 'Payment recorded successfully',
    'getting_table_id' => $get_table_id,
    'payment_method' => $payment_method,
    'total_payment' => $total_payment
]);
?>
