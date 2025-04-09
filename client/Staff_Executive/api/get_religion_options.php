<?php
include dirname(__FILE__) . '/../../../config/connect_db.php';

$sql = "SELECT DISTINCT religion FROM customer WHERE religion IS NOT NULL AND religion != ''";
$result = mysqli_query($conn, $sql);

$options = [];

while ($row = mysqli_fetch_assoc($result)) {
  $religion = $row['religion'];
  $options[] = ['id' => $religion, 'name' => $religion];
}

echo json_encode($options);
