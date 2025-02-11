<?php
header("Content-Type: application/json");
$servername = "localhost";
$username = "root";
$password = "123456";
$dbname = "winaishabu";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Connection failed: " . $conn->connect_error]));
}

// รับข้อมูลจาก JSON
$data = json_decode(file_get_contents("php://input"), true);

$table_id = $data["table_id"];
$package_id = $data["package_id"];
$promotion_id = $data["promotion_id"];
$package_price = $data["package_price"];
$payment_method = $data["payment_method"];

// บันทึกข้อมูลลงในตาราง payment
$sql = "INSERT INTO payment_table (table_id, package_id, promotion_id, amount, payment_method, status) 
        VALUES (?, ?, ?, ?, ?, 'pending')";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iiids", $table_id, $package_id, $promotion_id, $package_price, $payment_method);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Error: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
