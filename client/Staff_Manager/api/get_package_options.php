<?php
include dirname(__FILE__) . '/../../../config/connect_db.php';

$sql = "SELECT package_id, package_name FROM package ORDER BY package_name ASC";
$result = mysqli_query($conn, $sql);

$options = [];

while ($row = mysqli_fetch_assoc($result)) {
    $options[] = [
        'id' => $row['package_id'],
        'name' => $row['package_name']
    ];
}

echo json_encode($options);
