<?php
date_default_timezone_set("Asia/Bangkok");
include dirname(__FILE__) . '/include/header.php';
include dirname(__FILE__) . '/../../config/connect_db.php';

// รับจาก URL
$walkin_id = $_GET['id'] ?? '';

// รับจาก POST
$first_name = $_POST['first_name'] ?? '';
$last_name = $_POST['last_name'] ?? '';
$table_number = $_POST['table_number'] ?? '';
$time_slot = $_POST['time_slot'] ?? '';
$number_of_guest = $_POST['number_of_guest'] ?? '';
$table_id = $_POST['table_id'] ?? '';

//echo "<pre>";
//print_r($_POST);
//echo "</pre>";

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
                <h2 class="mb-4">รับโต๊ะ <?php echo htmlspecialchars($table_number); ?> (Walk-in id: <?php echo htmlspecialchars($walkin_id); ?>)</h2>
                <form action="process_selection_walkin.php" method="POST">

                    <!-- Hidden Walk-in Data -->
                    <input type="hidden" name="walkin_id" value="<?php echo htmlspecialchars($walkin_id); ?>">
                    <input type="hidden" name="first_name" value="<?php echo htmlspecialchars($first_name); ?>">
                    <input type="hidden" name="last_name" value="<?php echo htmlspecialchars($last_name); ?>">
                    <input type="hidden" name="table_number" value="<?php echo htmlspecialchars($table_number); ?>">
                    <input type="hidden" name="time_slot" value="<?php echo htmlspecialchars($time_slot); ?>">
                    <input type="hidden" name="number_of_guest" value="<?php echo htmlspecialchars($number_of_guest); ?>">
                    <input type="hidden" name="table_id" value="<?php echo htmlspecialchars($table_id); ?>">

                    <!--<input type="hidden" name="reservation_id" value="<?php echo isset($_GET["reservation_id"]) ? htmlspecialchars($_GET["reservation_id"]) : ''; ?>">-->

                    <div class="mb-3">
                        <label for="package" class="form-label"><strong>เลือกแพ็กเกจ:</strong></label>
                        <select class="form-control" name="package_id" required>
                            <option value="">-- กรุณาเลือกแพ็กเกจ --</option>
                            <?php
                            $sql = "SELECT * FROM package ORDER BY package_id ASC";
                            $result = mysqli_query($conn, $sql);

                            while ($row = mysqli_fetch_assoc($result)) {
                                $package_id = htmlspecialchars($row['package_id']);
                                $package_name = htmlspecialchars($row['package_name']);

                                echo "<option value=\"$package_id\">$package_name</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <?php
                    $sql = "
    SELECT 
        p.promotion_id, 
        p.promotions_name, 
        pi.quantity 
    FROM 
        promotion p
    JOIN 
        promotion_item pi ON p.promotion_id = pi.promotion_id
    WHERE 
        (pi.for_customer_type = 'walk_in' OR pi.for_customer_type = 'both')
        AND pi.status = 'active'
";
                    $result = $conn->query($sql);
                    ?>

                    <select class="form-control" name="promotion_id">
                        <?php while ($row = $result->fetch_assoc()) {
                            $promotion_id = $row['promotion_id'];
                            $name = htmlspecialchars($row['promotions_name'], ENT_QUOTES, 'UTF-8');
                            $quantity = is_null($row['quantity']) ? '' : " (คงเหลือ: {$row['quantity']})";
                        ?>
                            <option value="<?= htmlspecialchars($promotion_id, ENT_QUOTES, 'UTF-8') ?>">
                                <?= $name . $quantity ?>
                            </option>
                        <?php } ?>
                    </select>

                    <!-- กำหนดค่ารหัสพนักงานโดยอัตโนมัติ -->
                    <?php
                    $sql_random_employee = "SELECT employee_id FROM employee ORDER BY RAND() LIMIT 1";
                    $result = mysqli_query($conn, $sql_random_employee);
                    $employee_id = '';

                    if ($result && mysqli_num_rows($result) > 0) {
                        $row = mysqli_fetch_assoc($result);
                        $employee_id = $row['employee_id'];
                    }
                    ?>
                    <input type="hidden" name="employee_id" value="<?php echo htmlspecialchars($employee_id); ?>">

                    <div class="mt-4">
                        <button type="submit" class="btn btn-success">ยืนยันการเลือก</button>
                        <a href="index.php" class="btn btn-secondary">ย้อนกลับ</a>
                    </div>
                </form>

        </div>
        </main>
</body>
<?php include dirname(__FILE__) . '/include/footer.php'; ?>