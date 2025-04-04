<?php
include dirname(__FILE__) . '/../../config/connect_db.php';

$input = json_decode(file_get_contents("php://input"), true);
$notificationId = intval($input['id']);
$employeeId = intval($input['employee_id']);

$sql = "UPDATE notification 
        SET status = 'read', employee_id = $employeeId 
        WHERE Notification = $notificationId 
        AND status = 'unread'";

mysqli_query($conn, $sql);
http_response_code(200);
