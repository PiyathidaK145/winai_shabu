<?php
include dirname(__FILE__) . '/../../config/connect_db.php';

$code = $_GET['code'] ?? '';

$response = ['found' => false];

if ($code !== '') {
    $sql = "SELECT first_name, last_name, guest_amount 
            FROM reservation 
            WHERE booking_code = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $code);
    $stmt->execute();
    $stmt->bind_result($first_name, $last_name, $guests);

    if ($stmt->fetch()) {
        $response = [
            'found' => true,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'guests' => $guests
        ];
    }
    $stmt->close();
}

header('Content-Type: application/json');
echo json_encode($response);
