<?php
include '../../config/connect_db.php';

$category_id = $_GET['category_id'] ?? '';

if ($category_id) {
    $sql = "SELECT supplier_id, name FROM supplier WHERE category_id = '$category_id'";
    $result = mysqli_query($conn, $sql);

    $suppliers = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $suppliers[] = $row;
    }

    echo json_encode($suppliers);
}
?>
