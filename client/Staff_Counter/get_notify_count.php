<?php
include dirname(__FILE__) . '/../../config/connect_db.php';
date_default_timezone_set("Asia/Bangkok");
$today = date('Y-m-d');

$sql = "SELECT COUNT(Notification) as total, created_at FROM notification 
        WHERE role_id = 206 AND status = 'unread' 
        AND DATE(created_at) = '$today'";

$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

echo $row['total']; // return ตัวเลข
