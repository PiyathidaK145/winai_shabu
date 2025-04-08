<?php
include dirname(__FILE__) . '/../../../config/connect_db.php';

$sql = "SELECT DISTINCT gender FROM customer WHERE gender IS NOT NULL AND gender != ''";
$result = mysqli_query($conn, $sql);

$options = [];

while ($row = mysqli_fetch_assoc($result)) {
  $gender = $row['gender'];
  $options[] = ['id' => $gender, 'name' => $gender];
}

echo json_encode($options);
