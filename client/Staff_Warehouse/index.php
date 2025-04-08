<?php
date_default_timezone_set("Asia/Bangkok");
include dirname(__FILE__) . '/include/header.php';
include dirname(__FILE__) . '/../../config/connect_db.php';

$today = date("Y-m-d");
$current_time = date("H:i:s");
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>หน้าหลัก</title>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Global CSS (Bootstrap assumed loaded in header.php) -->
    <link rel="stylesheet" href="assets/css/summary_cards.css">
    <link rel="stylesheet" href="assets/css/ingredient_analytics.css">
    <link rel="stylesheet" href="assets/css/recent_imports.css">
    <link rel="stylesheet" href="assets/css/top_suppliers.css">
    <link rel="stylesheet" href="assets/css/expiring_ingredients.css">
    <link rel="stylesheet" href="assets/css/calendar_expiry.css">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <main class="main-wrapper col-md-9 ms-sm-auto py-4 col-lg-9 px-md-4 border-start">
                <div>
                    <!-- ✅ Section 1: Summary Cards -->
                    <?php include 'index_summary_cards.php'; ?>
                </div>


                <div>
                    <!-- ✅ Section 2: Ingredient Analytics -->
                    <?php include 'index_ingredient_analytics.php'; ?>
                </div>

                <div class="container">
                    <div class="row">

                        <!-- ✅ Section 3: Recent Imports -->
                        <div class="col-md-6">
                            <?php include 'index_recent_imports.php'; ?>
                        </div>

                        <!-- ✅ Section 4: Top Suppliers -->
                        <div class="col-md-6">
                            <?php include 'index_top_suppliers.php'; ?>
                        </div>

                    </div>
                </div>

                <div class="col">
                    <?php include 'index_expiring_ingredients.php'; ?>
                </div>
            </main>
        </div>
    </div>

    <!-- JS Scripts -->
    <script src="assets/js/summary_cards.js"></script>
    <script src="assets/js/ingredient_analytics.js"></script>
    <script src="assets/js/recent_imports.js"></script>
    <script src="assets/js/top_suppliers.js"></script>
    <script src="assets/js/expiring_ingredients.js"></script>


    <?php include dirname(__FILE__) . '/include/footer.php';; ?>

</body>



</html>