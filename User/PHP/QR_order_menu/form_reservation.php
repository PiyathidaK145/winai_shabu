<?php

include '../../../config/connect_db.php';
mysqli_set_charset($conn, "utf8");

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $getting_table_id = $_POST['getting_table_id'];

    $sql = "SELECT package_id FROM getting_table WHERE getting_table_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $getting_table_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($package_id);
        $stmt->fetch();
        // ส่งแค่ getting_table_id และ package_id ไปหน้า menu_selac.php
        header("Location: menu_selac.php?getting_table_id=$getting_table_id&package_id=$package_id");
        exit();
    } else {
        $error_message = "❌ ไม่พบข้อมูล Getting Table นี้!";
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ตรวจสอบรหัสการจอง</title>
    <link rel="stylesheet" href="../../CSS/style.css">
</head>

<body>
    <div class="container">
        <h2>กรอกรหัสการจอง</h2>
        <form method="POST">
            <input type="number" name="getting_table_id" placeholder="รหัส Getting Table" required>
            <button type="submit">ตรวจสอบ</button>
        </form>

        <?php if (!empty($error_message)) { ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php } ?>
    </div>
</body>

</html>