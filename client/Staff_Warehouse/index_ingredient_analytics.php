<?php
include dirname(__FILE__) . '/../../config/connect_db.php';

// --- 1. จำนวนวัตถุดิบทั้งหมด (capacity * quantity_of_sale) ---
$sql1 = "
    SELECT SUM(r.capacity * m.quantity_of_sale) AS total_capacity, m.unit
    FROM raw_material r
    LEFT JOIN menu m ON r.raw_material_id = m.raw_material_id
";
$res1 = mysqli_query($conn, $sql1);
$data1 = mysqli_fetch_assoc($res1);
$total_ingredients = number_format($data1['total_capacity']) . ' ' . $data1['unit'];

// --- 2. จำนวนครั้งการนำเข้า (นับจำนวนแถว)
$sql2 = "SELECT COUNT(*) AS count_imports FROM import_raw_material";
$res2 = mysqli_query($conn, $sql2);
$data2 = mysqli_fetch_assoc($res2);
$count_imports = number_format($data2['count_imports']);

// --- 3. ปริมาณการนำเข้า (quantity * raw_material.quanity)
$sql3 = "
    SELECT SUM(c.capacity * m.quantity_of_sale) AS total_volume, m.unit
    FROM calculate_raw_material c
    LEFT JOIN import_raw_material i ON c.import_raw_material_id = i.import_raw_material_id
    LEFT JOIN menu m ON i.menu_id = m.menu_id
    LEFT JOIN raw_material r ON m.raw_material_id = r.raw_material_id
";
$res3 = mysqli_query($conn, $sql3);
$data3 = mysqli_fetch_assoc($res3);
$total_volume = number_format($data3['total_volume']) . ' ' . $data3['unit'];

// --- 4. จำนวนค่าใช้จ่าย (sum cost)
$sql4 = "SELECT SUM(cost) AS total_cost FROM import_raw_material";
$res4 = mysqli_query($conn, $sql4);
$data4 = mysqli_fetch_assoc($res4);
$total_cost = number_format($data4['total_cost']) . ' บาท';
?>


<!-- CSS สำหรับ style -->
<link rel="stylesheet" href="assets/css/ingredient_analytics.css">

<div class="ingredient-analytics-container mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>การวิเคราะห์วัตถุดิบ</h4>
        <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#ingredientFilterModal">Filter</button>
    </div>

    <div class="d-flex flex-wrap gap-3">
        <!-- ปุ่มด้านซ้าย -->
        <div class="button-sidebar d-flex flex-column gap-3 me-4">
            <button class="btn" onclick="showChart('ingredients')">
                ปริมาณวัตถุดิบทั้งหมด<br><strong id="count-ingredients">-</strong>
            </button>

            <button class="btn" onclick="showChart('times')">
                จำนวนครั้งการนำเข้า<br><strong id="count-imports">-</strong>
            </button>

            <button class="btn" onclick="showChart('volume')">
                ปริมาณการนำเข้า<br><strong id="count-volume">-</strong>
            </button>

            <button class="btn" onclick="showChart('cost')">
                จำนวนค่าใช้จ่าย<br><strong id="count-cost">-</strong>
            </button>
        </div>



        <!-- พื้นที่แสดงกราฟ -->
        <div class="flex-grow-1">
            <div id="chart-ingredients" class="chart-box"><?php include 'index_charts/chart_ingredients_count.php'; ?></div>
            <div id="chart-times" class="chart-box d-none"><?php include 'index_charts/chart_import_times.php'; ?></div>
            <div id="chart-volume" class="chart-box d-none"><?php include 'index_charts/chart_import_volume.php'; ?></div>
            <div id="chart-cost" class="chart-box d-none"><?php include 'index_charts/chart_import_cost.php'; ?></div>
        </div>

    </div>
</div>


<!-- Modal Filter -->
<?php include 'index_modals/modal_filter_ingredients.php'; ?>

<!-- JS -->
<script src="assets/js/ingredient_analytics.js"></script>
