<?php
include dirname(__FILE__) . '/../../config/connect_db.php';

$start_date = $_POST['start_date'] ?? '';
$end_date = $_POST['end_date'] ?? '';
$storage_id = $_POST['storage_id'] ?? '';
$category_id = $_POST['category_id'] ?? '';

$sql = "
    SELECT irm.create_at AS import_date, rm.item_name AS ingredient_name, rm.unit,
           irm.quantity, irm.cost, s.name AS supplier_name
    FROM import_raw_material irm
    JOIN menu m ON irm.menu_id = m.menu_id
    JOIN raw_material rm ON m.raw_material_id = rm.raw_material_id
    JOIN supplier s ON rm.supplier_id = s.supplier_id
    WHERE 1
";

if (!empty($start_date)) {
    $sql .= " AND DATE(irm.create_at) >= '" . mysqli_real_escape_string($conn, $start_date) . "'";
}
if (!empty($end_date)) {
    $sql .= " AND DATE(irm.create_at) <= '" . mysqli_real_escape_string($conn, $end_date) . "'";
}
if (!empty($storage_id)) {
    $sql .= " AND rm.warehouse_id = '" . mysqli_real_escape_string($conn, $storage_id) . "'";
}
if (!empty($category_id)) {
    $sql .= " AND rm.category_id = '" . mysqli_real_escape_string($conn, $category_id) . "'";
}

$sql .= " ORDER BY irm.create_at DESC LIMIT 5";

$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>
            <td>" . date("d/m/Y", strtotime($row['import_date'])) . "</td>
            <td>{$row['ingredient_name']}</td>
            <td>{$row['unit']}</td>
            <td>{$row['quantity']}</td>
            <td>" . number_format($row['cost'], 2) . "</td>
            <td>{$row['supplier_name']}</td>
          </tr>";
}
?>
