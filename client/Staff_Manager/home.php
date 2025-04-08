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
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <main class="main-wrapper col-md-9 ms-sm-auto py-4 col-lg-9 px-md-4 border-start">
                <div class="container text-center">
                    <h2 class="mb-4">Dashboard</h2>
                    <div class="d-grid gap-3 d-md-block">
                        <a href="index_sales.php" class="btn btn-primary btn-lg me-3">Dashboard ยอดขาย</a>
                        <a href="index_warehouse.php" class="btn btn-success btn-lg">Dashboard คลังวัตถุดิบ</a>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
<?php include dirname(__FILE__) . '/include/footer.php'; ?>