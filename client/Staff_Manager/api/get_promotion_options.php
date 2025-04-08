<?php
include dirname(__FILE__) . '/../../../config/connect_db.php';

$sql = "SELECT promotion_id AS id, promotions_name FROM promotion ORDER BY promotions_name ASC";
$result = mysqli_query($conn, $sql);

$options = [];

while ($row = mysqli_fetch_assoc($result)) {
    $options[] = [
        'id' => $row['id'],
        'name' => $row['promotions_name']
    ];
}

echo json_encode($options);
