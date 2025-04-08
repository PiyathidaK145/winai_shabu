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

</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <main class="main-wrapper col-md-9 ms-sm-auto py-4 col-lg-9 px-md-4 border-start">
                <?php include 'components/summary_overview.php'; ?>

                <div class="container">
                    <div class="row">
                        <div class="col-md-6">
                            <?php include 'components/customer_service_chart.php'; ?>
                        </div>

                        <div class="col-md-6">
                            <?php include 'components/package_chart.php'; ?>
                        </div>

                    </div>
                </div>

                <div class="container">
                    <div class="row">
                        <div class="col-md-6">
                            <?php include 'components/promotion_chart.php'; ?>
                        </div>

                        <div class="col-md-6">
                            <?php include 'components/popular_service.php'; ?>
                        </div>

                    </div>
                </div>


                <?php include 'components/service_analysis.php'; ?>
                <?php include 'components/satisfaction_analysis.php'; ?>
            </main>
        </div>
    </div>



    <?php include dirname(__FILE__) . '/include/footer.php';; ?>

</body>



</html>