<?php
include dirname(__FILE__) . '/../../config/connect_db.php';

$start_date = $_POST['start_date'] ?? '';
$end_date = $_POST['end_date'] ?? '';
$warehouse_id = $_POST['storage_id'] ?? '';
$category_id = $_POST['category_id'] ?? '';

$today = date("Y-m-d");
$seven_days_later = date("Y-m-d", strtotime("+7 days"));

$where = "WHERE DATE(c.expried_date) BETWEEN '$today' AND '$seven_days_later'";

if ($start_date && $end_date) {
    $where = "WHERE DATE(c.expried_date) BETWEEN '$start_date' AND '$end_date'";
}
if ($warehouse_id) {
    $where .= " AND w.warehouse_id = '$warehouse_id'";
}
if ($category_id) {
    $where .= " AND rm.category_id = '$category_id'";
}

$sql = "
    SELECT 
        c.capacity,
        c.expried_date,
        rm.item_name,
        rm.unit,
        w.name AS warehouse_name
    FROM calculate_raw_material c
    INNER JOIN import_raw_material i ON c.import_raw_material_id = i.import_raw_material_id
    INNER JOIN menu m ON i.menu_id = m.menu_id
    INNER JOIN raw_material rm ON m.raw_material_id = rm.raw_material_id
    INNER JOIN warehouse w ON rm.warehouse_id = w.warehouse_id
    $where
    ORDER BY c.expried_date ASC
    LIMIT 5
";

$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>
            <td>{$row['item_name']}</td>
            <td>{$row['capacity']}</td>
            <td>{$row['unit']}</td>
            <td>" . date("d/m/Y", strtotime($row['expried_date'])) . "</td>
            <td>{$row['warehouse_name']}</td>
          </tr>";
}
?>
