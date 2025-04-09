<?php
include dirname(__FILE__) . '/../../../config/connect_db.php';

$sql = "SELECT table_id FROM `table` ORDER BY table_id ASC";
$result = mysqli_query($conn, $sql);

$options = [];

while ($row = mysqli_fetch_assoc($result)) {
  $options[] = [
    'id' => $row['table_id'],
    'name' => 'โต๊ะ ' . $row['table_id']
  ];
}

echo json_encode($options);
