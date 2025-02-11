<?php
require 'order_handler.php'; // เรียกใช้ฟังก์ชันจาก order_handler.php

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $orderItems = json_decode(file_get_contents("php://input"), true);

    if (!$orderItems || !is_array($orderItems)) {
        echo json_encode(["status" => "error", "message" => "Invalid data"]);
        exit;
    }

    $response = insertOrder($orderItems); // เรียกฟังก์ชันจาก order_handler.php
    echo json_encode($response); // ส่ง JSON กลับไปให้ Frontend
}
?>
