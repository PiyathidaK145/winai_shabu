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

            </main>
        </div>
    </div>
</body>
<?php include dirname(__FILE__) . '/include/footer.php'; ?>