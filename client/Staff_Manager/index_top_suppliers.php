<?php include dirname(__FILE__) . '/../../config/connect_db.php'; ?>
<link rel="stylesheet" href="assets/css/top_suppliers.css">

<div class="top-suppliers-container mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="text-dark fw-bold">Top 3 ซัพพลายเออร์</h4>
        <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#supplierFilterModal">Filter</button>
    </div>

    <div class="row row-cols-1 g-3">
        <?php
        $sql = "
        SELECT 
            s.name AS supplier_name, 
            SUM(i.quantity * r.quanity) AS total_quantity,
            SUM(i.cost) AS total_price, 
            COUNT(*) AS import_count
        FROM import_raw_material i
        INNER JOIN menu m ON i.menu_id = m.menu_id
        INNER JOIN raw_material r ON m.raw_material_id = r.raw_material_id
        INNER JOIN supplier s ON r.supplier_id = s.supplier_id
        GROUP BY s.supplier_id
        ORDER BY total_quantity DESC
        LIMIT 3
    ";

        $result = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            $total_price = isset($row['total_price']) ? number_format($row['total_price'], 2) : '0.00';
            $total_quantity = isset($row['total_quantity']) ? $row['total_quantity'] : '0';
            $import_count = isset($row['import_count']) ? $row['import_count'] : '0';

            echo '<div class="col">
                <div class="card h-100 text-dark border shadow-sm">
                    <div class="card-header fw-bold bg-light">' . $row['supplier_name'] . '</div>
                    <div class="card-body small">
                        <p class="card-text text-dark mb-1">รวมมูลค่า: <strong>' . $total_price . ' บาท</strong></p>
                        <p class="card-text text-dark mb-1">รวมปริมาณการสั่งซื้อ: ' . $total_quantity . '</p>
                        <p class="card-text text-dark mb-0">จำนวนครั้ง: ' . $import_count . '</p>
                    </div>
                </div>
              </div>';
        }
        ?>
    </div>
</div>

<?php include 'index_modals/modal_filter_suppliers.php'; ?>
<script src="assets/js/top_suppliers.js"></script>