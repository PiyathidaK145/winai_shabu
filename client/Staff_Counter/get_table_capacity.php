<?php
include '../../config/connect_db.php';

header('Content-Type: application/json'); 

if (isset($_GET['table_id'])) {
    $table_id = intval($_GET['table_id']);

    $sql = "SELECT capacity 
            FROM `table` t 
            JOIN table_type tt ON t.table_type_id = tt.table_type_id 
            WHERE t.table_id = $table_id";

    $result = mysqli_query($conn, $sql);

    if ($row = mysqli_fetch_assoc($result)) {
        echo json_encode(['capacity' => (int)$row['capacity']]);
        mysqli_close($conn); 
        exit; 
    } else {
        echo json_encode(['capacity' => 0]);
        mysqli_close($conn);
        exit;
    }
} else {
    echo json_encode(['capacity' => 0]);
    mysqli_close($conn);
    exit;
}
?>
