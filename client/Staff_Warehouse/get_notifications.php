<?php
include dirname(__FILE__) . '/../../config/connect_db.php';
date_default_timezone_set("Asia/Bangkok");
$today = date('Y-m-d');
$sort = $_GET['sort'] === 'asc' ? 'ASC' : 'DESC';

$sql = "SELECT Notification, Notification_name, status, created_at 
        FROM notification 
        WHERE role_id = 204 
        AND DATE(created_at) = '$today' 
        ORDER BY created_at $sort";

$result = mysqli_query($conn, $sql);
$data = [];

while ($row = mysqli_fetch_assoc($result)) {
    $data[] = [
        'id' => $row['Notification'],
        'message' => $row['Notification_name'],
        'status' => $row['status'],
        'time' => date('d/m/Y H:i:s', strtotime($row['created_at']))
    ];
}

header('Content-Type: application/json');
echo json_encode($data);
