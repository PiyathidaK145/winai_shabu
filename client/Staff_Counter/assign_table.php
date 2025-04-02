<?php
include dirname(__FILE__) . '/include/header.php';
include dirname(__FILE__) . '/../../config/connect_db.php';
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เลือกโต๊ะ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <main class="main-wrapper col-md-9 ms-sm-auto py-4 col-lg-9 px-md-4 border-start">
                <h1 class="h2 mb-0">เลือกโต๊ะ</h1>
                <form action="show_reservation.php" method="GET">
                    <div class="mb-3">
                        <label for="reservation_id" class="form-label">รหัสการจอง</label>
                        <input type="number" class="form-control" id="reservation_id" name="reservation_id" required>
                    </div>
                    <button type="submit" class="btn btn-primary">ตรวจสอบ</button>
                </form>
</body>
<?php include dirname(__FILE__) . '/include/footer.php'; ?>