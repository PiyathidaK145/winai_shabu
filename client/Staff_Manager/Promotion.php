<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_GET['ajax'])) {
    include 'include/header.php';
}

include dirname(__FILE__) . '/../../config/connect_db.php';

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);$pdo = $conn;

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];
// ดึงค่า filter จากฟอร์มค้นหา
$forCustomerType = isset($_GET['for_customer_type']) ? $_GET['for_customer_type'] : '';
$countdownStatus = isset($_GET['countdown_status']) ? $_GET['countdown_status'] : '';
$searchTerm = isset($_GET['search_term']) ? $_GET['search_term'] : '';

// เงื่อนไข SQL เริ่มต้น
$query = "
    SELECT
        pi.promotion_item_id,
        p.promotions_name,
        p.description AS promotion_description,
        rm.item_name,
        pi.for_customer_type,
        pi.discount_value,
        pi.discount_type,
        pi.start_date,
        pi.end_date,
        pi.quantity,
        CASE
            WHEN pi.end_date < NOW() THEN 'expired'
            WHEN pi.end_date >= NOW() THEN 'active'
        END AS status
    FROM promotion_item pi
    JOIN promotion p ON pi.promotion_id = p.promotion_id
    LEFT JOIN menu m ON pi.menu_id = m.menu_id
    LEFT JOIN raw_material rm ON m.raw_material_id = rm.raw_material_id
    WHERE 1=1
";

// เพิ่มเงื่อนไขจาก filter
if ($forCustomerType) {
    $query .= " AND pi.for_customer_type = :for_customer_type";
}
if ($countdownStatus === 'expired') {
    $query .= " AND pi.end_date < NOW()";
} elseif ($countdownStatus === 'active') {
    $query .= " AND pi.end_date >= NOW()";
}
if ($searchTerm) {
    $query .= " AND (p.promotions_name LIKE :search_term OR p.description LIKE :search_term OR rm.item_name LIKE :search_term OR pi.for_customer_type LIKE :search_term)";
}

// เพิ่ม GROUP BY
$query .= " GROUP BY pi.promotion_id";

// เตรียม statement
$stmt = $conn->prepare($query);

// Bind parameters ตามที่มี
if ($forCustomerType) {
    $stmt->bindParam(':for_customer_type', $forCustomerType);
}
if ($searchTerm) {
    $searchTermWithWildcards = "%" . $searchTerm . "%";
    $stmt->bindParam(':search_term', $searchTermWithWildcards);
}

// รัน query
$stmt->execute();
$promotionItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Promotions</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        /* ปรับขนาดตัวหนังสือในตาราง */
        .table td,
        .table th {
            font-size: 8px;
            /* ลดขนาดตัวอักษรในตาราง */
            vertical-align: middle;
        }

        /* ปรับหัวคอลัมน์ */
        .table th {
            font-size: 10px;
            /* ขนาดตัวอักษรในหัวคอลัมน์ */
            text-align: center;
            vertical-align: middle;
            background-color: rgb(31, 0, 0);
        }

        .countdown {
            font-weight: bold;
            color: red;
        }

        .table td.description,
        .table td.menu-name {
            max-width: 200px;
            white-space: normal;
            word-wrap: break-word;
        }

        /* สไตล์สำหรับเพิ่มคอลัมลำดับ */
        .table td.index {
            text-align: center;
        }

        /* แก้ไขการแสดงไอคอนลูกศร */
        .sort-arrow {
            margin-left: 5px;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <main class="main-wrapper col-md-9 ms-sm-auto py-4 col-lg-9 px-md-4 border-start">
                <div class="mb-4"></div>
                <h1>โปรโมชั่น</h1>
                <a href="add_promotion.php" class="btn btn-primary mb-3">เพิ่มโปรโมชั่น</a>

                <!-- ฟอร์มฟิลเตอร์ -->
                <form method="get" class="mb-3">
                    <div class="row">
                    <div class="col-md-3">
                            <select name="for_customer_type" class="form-select" onchange="this.form.submit()">
                                <option value="">-- Select Customer Type --</option>
                                <option value="walk_in" <?= $forCustomerType == 'walk_in' ? 'selected' : '' ?>>Walk-in</option>
                                <option value="reservation" <?= $forCustomerType == 'reservation' ? 'selected' : '' ?>>Reservation</option>
                                <option value="both" <?= $forCustomerType == 'both' ? 'selected' : '' ?>>Both</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <select name="countdown_status" class="form-select" onchange="this.form.submit()">
                                <option value="">-- Select Countdown Status --</option>
                                <option value="active" <?= $countdownStatus == 'active' ? 'selected' : '' ?>>Active</option>
                                <option value="expired" <?= $countdownStatus == 'expired' ? 'selected' : '' ?>>Expired</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="search_term" class="form-control" placeholder="Search by Name, Description, or Menu" value="<?= htmlspecialchars($searchTerm) ?>">
                        </div>

                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary">ค้นหา</button>
                        </div>
                    </div>
                </form>
                <div class="table-responsive">
                    <table id="tableUse" class="table_use table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th onclick="sortTable(0)"># <i class="fas fa-sort-up sort-arrow" id="sort-arrow-0"></i></th>
                                <th>ชื่อโปรโมชั่น</th>
                                <th>คำอธิบาย</th>
                                <th>ลูกค้า</th>
                                <th>ส่วนลด</th>
                                <th onclick="sortTable(6)">เวลาเริ่ม <i class="fas fa-sort-up sort-arrow" id="sort-arrow-6"></i></th>
                                <th onclick="sortTable(7)">เวลาสิ้นสุด <i class="fas fa-sort-up sort-arrow" id="sort-arrow-7"></i></th>
                                <th>นับเวลาถอยหลัง</th>
                                <th>จำนวนโปรโมชั่น</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $index = 1; ?> <!-- เริ่มต้นที่ 1 สำหรับลำดับ -->
                            <?php foreach ($promotionItems as $promo): ?>
                                <tr>
                                    <td class="index"><?= $index++ ?></td> <!-- แสดงลำดับ -->
                                    <td><?= htmlspecialchars($promo['promotions_name'] ?? '-') ?></td>
                                    <td style="white-space: normal; max-width: 200px;"><?= htmlspecialchars($promo['promotion_description'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($promo['for_customer_type'] ?? '-') ?></td>
                                    <td> <?php
                                            // ตรวจสอบว่า discount_type เป็น percentage หรือไม่
                                            if ($promo['discount_type'] === 'percentage') {
                                                echo htmlspecialchars($promo['discount_value']) . '%';
                                            } else {
                                                echo htmlspecialchars($promo['discount_value'] ?? '-');
                                            }
                                            ?></td>
                                    <td><?= htmlspecialchars($promo['start_date'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($promo['end_date'] ?? '-') ?></td>
                                    <td class="countdown" id="countdown_<?= $promo['promotion_item_id'] ?>" data-end-date="<?= $promo['end_date'] ?>"></td>
                                    <td><?= $promo['quantity'] ?></td>
                                    <td>
                                        <a href="Edit_promotion.php?promotion_item_id=<?=  $promo['promotion_item_id'] ?>" class="btn btn-warning">แก้ไข</a>
                                        <a href="delete_promotion.php?id=<?= $promo['promotion_item_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this promotion?')">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    <script>
        
        let currentSortColumn = null;
        let ascending = true;

        function sortTable(columnIndex) {
            const table = document.getElementById("tableUse");
            const rows = Array.from(table.rows).slice(1);

            const sortedRows = rows.sort((rowA, rowB) => {
                const cellA = rowA.cells[columnIndex].innerText.toLowerCase();
                const cellB = rowB.cells[columnIndex].innerText.toLowerCase();

                if (cellA < cellB) return ascending ? -1 : 1;
                if (cellA > cellB) return ascending ? 1 : -1;
                return 0;
            });

            sortedRows.forEach(row => table.appendChild(row));

            // Update the sort arrows
            updateSortArrows(columnIndex);
        }

        function updateSortArrows(columnIndex) {
            const arrows = document.querySelectorAll('.sort-arrow');
            arrows.forEach(arrow => arrow.classList.remove('fa-sort-up', 'fa-sort-down'));

            const currentArrow = document.getElementById(`sort-arrow-${columnIndex}`);
            if (ascending) {
                currentArrow.classList.add('fa-sort-down');
            } else {
                currentArrow.classList.add('fa-sort-up');
            }

            ascending = !ascending;
        }

        function updateCountdown() {
            const countdownElements = document.querySelectorAll('.countdown');
            countdownElements.forEach(function(element) {
                const endDate = new Date(element.getAttribute('data-end-date'));
                const now = new Date();
                const timeLeft = endDate - now;

                if (timeLeft <= 0) {
                    if (element.textContent !== "Expired") {
                        element.textContent = "Expired";
                        element.style.color = 'gray';

                        // อัปเดตสถานะผ่าน Ajax
                        const promotionItemId = element.id.split('_')[1];
                        fetch('ajax_update_status.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            body: 'promotion_item_id=' + encodeURIComponent(promotionItemId)
                        });
                    }
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
    <?php include dirname(__FILE__) . '/include/footer.php'; ?>
</body>

</html>