<?php
// ตั้งค่าข้อมูลโปรโมชั่น
$promotions = [
    ["id" => 1, "title" => "Promo 1", "description" => "ลด 10%", "start_date" => "2024-12-20 08:00:00", "end_date" => "2024-12-25 23:59:59"],
    ["id" => 2, "title" => "Promo 2", "description" => "Buy 1 Get 1", "start_date" => "2024-12-21 09:00:00", "end_date" => "2024-12-30 22:00:00"],
    ["id" => 3, "title" => "Promo 3", "description" => "ลด 20%", "start_date" => "2024-12-23 08:00:00", "end_date" => "2024-12-28 23:59:59"]
];

// กรองโปรโมชั่น
$filteredPromotions = $promotions;
if (isset($_POST['filter_type']) && is_array($_POST['filter_type'])) {
    $validFilters = array_column($promotions, 'description');
    $filterTypes = array_intersect($_POST['filter_type'], $validFilters);
    $filteredPromotions = array_filter($promotions, function ($promo) use ($filterTypes) {
        return in_array($promo['description'], $filterTypes);
    });
}

// ตั้งค่า timezone
date_default_timezone_set('Asia/Bangkok');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>โปรโมชั่น</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }
        h1 {
            font-family: 'Roboto', sans-serif;
            color: #333;
            text-align: center;
            margin-top: 20px;
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            margin-top: 30px;
            border-collapse: collapse;
        }
        table th, table td {
            padding: 15px;
            text-align: center;
            border: 1px solid #ddd;
        }
        table th {
            background-color: #f8f9fa;
        }
        .countdown {
            font-weight: bold;
            color: #ff4d4d;
            white-space: nowrap;
        }
        .btn-edit, .btn-delete {
            padding: 6px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-edit {
            background-color: rgb(223, 194, 30);
            color: dark;
        }
        .btn-edit:hover {
            background-color: rgb(174, 150, 19);
        }
        .btn-delete {
            background-color: #dc3545;
            color: white;
        }
        .btn-delete:hover {
            background-color: #c82333;
        }
        .table-responsive {
            overflow-x: auto;
        }
    </style>
</head>
<body>
<?php
if (file_exists('include/header.php')) {
    include 'include/header.php';
} else {
    echo "<p>Error: Header file not found.</p>";
}
?>
<div class="container-fluid">
    <div class="row">
        <main class="main-wrapper col-md-9 ms-sm-auto py-4 col-lg-9 px-md-4 border-start">
            <div class="container mt-5">
                <h1>โปรโมชั่น</h1>

                <a href="add_promotion.php" class="btn btn-primary mb-3">เพิ่มโปรโมชั่น</a>
                <div class="mb-3 d-flex justify-content-end">
                    <button class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#filterModal">การกรอง</button>
                </div>

                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>รายชื่อโปรโมชั่น</th>
                                <th>คำอธิบาย</th>
                                <th>วันที่เริ่มต้น</th>
                                <th>วันที่สิ้นสุด</th>
                                <th>เวลานับถอยหลัง</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($filteredPromotions as $promo): ?>
                                <tr>
                                    <td><?= htmlspecialchars($promo['title']) ?></td>
                                    <td><?= htmlspecialchars($promo['description']) ?></td>
                                    <td><?= $promo['start_date'] ?></td>
                                    <td><?= $promo['end_date'] ?></td>
                                    <td class='countdown' id='countdown_<?= $promo['id'] ?>' data-end-date='<?= $promo['end_date'] ?>'></td>
                                    <td>
                                        <a href='Edit_Promotion.php?id=<?= $promo['id'] ?>' class='btn btn-warning'>Edit</a>
                                        <a href='delete_promotion.php?id=<?= $promo['id'] ?>' class='btn btn-danger' onclick='return confirm("Are you sure you want to delete this promotion?")'>Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
            <script>
            function updateCountdown() {
                const countdownElements = document.querySelectorAll('.countdown');
                countdownElements.forEach(function(element) {
                    const endDate = new Date(element.getAttribute('data-end-date'));
                    const now = new Date();
                    const timeLeft = endDate - now;

                    if (timeLeft <= 0) {
                        element.textContent = "Expired";
                        element.style.color = 'gray';
                    } else {
                        const days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
                        const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
                        const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

                        element.textContent = `${days} days ${hours} hours ${minutes} minutes ${seconds} seconds`;
                    }
                });
            }
            setInterval(updateCountdown, 1000);
            </script>
        </main>
    </div>
</div>

<div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filterModalLabel">กรองข้อมูลโปรโมชั่น</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="Promotions.php">
                    <div class="mb-3">
                        <label class="form-label">โปรโมชั่น</label><br>
                        <?php foreach ($promotions as $promo): ?>
                            <input type="checkbox" id="filter_<?= $promo['id'] ?>" name="filter_type[]" value="<?= htmlspecialchars($promo['description']) ?>">
                            <label for="filter_<?= $promo['id'] ?>"><?= htmlspecialchars($promo['description']) ?></label><br>
                        <?php endforeach; ?>
                    </div>
                    <button type="submit" class="btn btn-primary">กรอง</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
