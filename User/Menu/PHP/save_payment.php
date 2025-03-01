<?php
header('Content-Type: application/json'); // กำหนดให้ Response เป็น JSON

// ปิดการแสดง Error/Warning ที่ไม่จำเป็น
error_reporting(0);
ini_set('display_errors', 0);

// เชื่อมต่อฐานข้อมูล
$servername = "localhost";
$username = "root";
$password = "123456";
$dbname = "a_shabu";

$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => "Connection failed: " . $conn->connect_error]);
    exit();
}

// รับข้อมูลจาก JavaScript
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['getting_table_id'], $data['payment_method'], $data['total_payment'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit();
}

$get_table_id = $data['getting_table_id'];
$payment_method = $data['payment_method'];
$total_payment = $data['total_payment'];

// ตรวจสอบว่ามีข้อมูลการชำระเงินอยู่แล้วหรือไม่
$count = 0; // แก้ไขการกำหนดค่าเริ่มต้นให้ $count
$check_stmt = $conn->prepare("SELECT COUNT(*) FROM payment WHERE getting_table_id = ?");
$check_stmt->bind_param("s", $get_table_id);
$check_stmt->execute();
$check_stmt->bind_result($count);
$check_stmt->fetch();
$check_stmt->close();

// ถ้าพบว่ามีรายการชำระเงินอยู่แล้ว ไม่อนุญาตให้เพิ่มซ้ำ
if ($count > 0) {
    echo json_encode([
        'success' => false,
        'message' => 'Payment for this table already exists'
    ]);
    exit();
}

// บันทึกข้อมูลการชำระเงินลงฐานข้อมูล
$stmt = $conn->prepare("INSERT INTO payment (getting_table_id, payment_method, total_payment) VALUES (?, ?, ?)");
$stmt->bind_param("ssi", $get_table_id, $payment_method, $total_payment);

if ($stmt->execute()) {
    echo json_encode([
        'success' => true,
        'message' => 'Payment recorded successfully',
        'getting_table_id' => $get_table_id,
        'payment_method' => $payment_method,
        'total_payment' => $total_payment
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to save payment']);
}

// ปิดการเชื่อมต่อ
$stmt->close();
$conn->close();
exit();
