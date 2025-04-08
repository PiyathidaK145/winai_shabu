<?php include dirname(__FILE__) . '/../../config/connect_db.php'; ?>
<link rel="stylesheet" href="assets/css/recent_imports.css">

<div class="recent-imports-container mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>รายการนำเข้าล่าสุด</h4>
        <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#importFilterModal">Filter</button>
    </div>

    <div class="table-responsive">
        <table class="table_use table-striped table-bordered align-middle">
            <thead class="table-dark text-center">
                    <tr>
                        <th>วันที่นำเข้า</th>
                        <th>ชื่อวัตถุดิบ</th>
                        <th>ปริมาณ</th>
                        <th>หน่วย</th>
                        <th>ราคา (บาท)</th>
                        <th>ซัพพลายเออร์</th>
                    </tr>
            </thead>
            <tbody class="text-center" id="recentImportsBody">
                <?php
                $sql = "
                SELECT 
                    irm.create_at AS import_date,
                    rm.item_name AS ingredient_name,
                    rm.unit,
                    rm.quanity,
                    irm.quantity,
                    irm.cost,
                    s.name AS supplier_name
                FROM import_raw_material irm
                JOIN menu m ON irm.menu_id = m.menu_id
                JOIN raw_material rm ON m.raw_material_id = rm.raw_material_id
                JOIN supplier s ON rm.supplier_id = s.supplier_id
                ORDER BY irm.create_at DESC
                LIMIT 5
            ";
                $result = mysqli_query($conn, $sql);
                while ($row = mysqli_fetch_assoc($result)) {
                    $total_amount = $row['quantity'] * $row['quanity'];
                    echo "<tr>
                            <td>" . date("d/m/Y", strtotime($row['import_date'])) . "</td>
                            <td>{$row['ingredient_name']}</td>
                            <td>" . number_format($total_amount, 2) . "</td>
                            <td>{$row['unit']}</td>
                            <td>" . number_format($row['cost'], 2) . "</td>
                            <td>{$row['supplier_name']}</td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'index_modals/modal_filter_imports.php'; ?>

<script src="assets/js/recent_imports.js"></script>




