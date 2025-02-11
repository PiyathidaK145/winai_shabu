<?php
$servername = "localhost";
$username = "root";
$password = "12345678";
$dbname = "winaishabu";

$conn = new mysqli($servername, $username, $password, $dbname);

function insertOrder($orderItems) {
    global $conn; // ใช้ตัวแปรฐานข้อมูลจาก db_connection.php

    $stmtFind = $conn->prepare("SELECT item_id FROM menu_item WHERE item_name = ?");
    $stmtInsert = $conn->prepare("INSERT INTO selecting_item (selecting_menu_id, selecting_item_name, quanity, created_at) VALUES (?, ?, ?, NOW())");

    foreach ($orderItems as $item) {
        $itemName = $item['name'];
        $quantity = $item['quantity'];

        $stmtFind->bind_param("s", $itemName);
        $stmtFind->execute();
        $stmtFind->bind_result($itemId);
        
        if ($stmtFind->fetch()) {
            $stmtInsert->bind_param("isi", $itemId, $itemName, $quantity);
            $stmtInsert->execute();
        }
    }
    
    return ["status" => "success", "message" => "Order submitted successfully"];
}
?>
