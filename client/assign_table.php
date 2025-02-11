<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เลือกโต๊ะ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">เลือกโต๊ะ</h2>
        <form action="show_reservation.php" method="GET">
            <div class="mb-3">
                <label for="reservation_id" class="form-label">รหัสการจอง</label>
                <input type="number" class="form-control" id="reservation_id" name="reservation_id" required>
            </div>
            <button type="submit" class="btn btn-primary">ตรวจสอบ</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
