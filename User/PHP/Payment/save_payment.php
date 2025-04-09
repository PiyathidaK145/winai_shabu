<?php
// เปิดการแสดงข้อผิดพลาดเพื่อช่วยในการดีบั๊ก
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../../../config/connect_db.php';

// ให้เซิร์ฟเวอร์อนุญาตการเรียกจากโดเมนอื่น (ในกรณีนี้คือ localhost:3000)
header("Access-Control-Allow-Origin: *");  // หรือใช้ 'http://localhost:3000' ถ้าต้องการจำกัดโดเมนเฉพาะ
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");  // อนุญาตเฉพาะเมธอด POST และ GET
header("Access-Control-Allow-Headers: Content-Type");  // อนุญาตให้มี Content-Type header

// โค้ด PHP ที่เหลือ...


// รับข้อมูลจาก JavaScript
$data = json_decode(file_get_contents('php://input'), true);

// ตรวจสอบการเชื่อมต่อฐานข้อมูล
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit();
}

// ตรวจสอบข้อมูลที่ได้รับจาก JavaScript
if (!isset($data['getting_table_id'], $data['payment_method'], $data['total_payment'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit();
}

$get_table_id = $data['getting_table_id'];
$payment_method = $data['payment_method'];
$total_payment = $data['total_payment'];

// ตรวจสอบว่ามีข้อมูลการชำระเงินอยู่แล้วหรือไม่
$count = 0; 
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
?>