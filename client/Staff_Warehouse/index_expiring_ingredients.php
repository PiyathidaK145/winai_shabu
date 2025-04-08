<?php include dirname(__FILE__) . '/../../config/connect_db.php'; ?>
<link rel="stylesheet" href="assets/css/expiring_ingredients.css">

<div class="expiring-ingredients-container mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>วัตถุดิบที่ใกล้หมดอายุ</h4>
        <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#expiryFilterModal">Filter</button>
    </div>

    <div class="table-responsive">
        <table class="table_use table-bordered table-striped text-center">
            <thead class="table-danger">
                <tr>
                    <th>ชื่อวัตถุดิบ</th>
                    <th>ปริมาณคงเหลือ</th>
                    <th>หน่วย</th>
                    <th>วันหมดอายุ</th>
                    <th>คลังจัดเก็บ</th>
                </tr>
            </thead>
            <tbody id="expiringTableBody">
                <?php
                $today = date("Y-m-d");
                $seven_days_later = date("Y-m-d", strtotime("+7 days"));

                $sql = "
                    SELECT 
                        c.calculate_raw_material_id,
                        c.import_raw_material_id,
                        c.capacity,
                        c.expried_date,
                        c.status,
                        i.quantity,
                        rm.item_name,
                        rm.quanity,
                        rm.unit,
                        w.name AS warehouse_name
                    FROM calculate_raw_material c
                    INNER JOIN import_raw_material i ON c.import_raw_material_id = i.import_raw_material_id
                    INNER JOIN menu m ON i.menu_id = m.menu_id
                    INNER JOIN raw_material rm ON m.raw_material_id = rm.raw_material_id
                    INNER JOIN warehouse w ON rm.warehouse_id = w.warehouse_id
                    WHERE DATE(c.expried_date) BETWEEN '$today' AND '$seven_days_later'
                    ORDER BY c.expried_date ASC
                    LIMIT 5
                ";

                $result = mysqli_query($conn, $sql);
                while ($row = mysqli_fetch_assoc($result)) {
                    $total_amount = $row['quantity'] * $row['quanity'];
                    echo "<tr>
                            <td>{$row['item_name']}</td>
                            <td>" . number_format($total_amount, 2) . "</td>
                            <td>{$row['unit']}</td>
                            <td>" . date("d/m/Y", strtotime($row['expried_date'])) . "</td>
                            <td>{$row['warehouse_name']}</td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'index_modals/modal_filter_expiry.php'; ?>
<script src="assets/js/expiring_ingredients.js"></script>
