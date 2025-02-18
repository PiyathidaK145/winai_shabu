<?php
$host = "localhost";
$user = "root";
$password = "123456";
$database = "winaishabu";

// เชื่อมต่อฐานข้อมูล
$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("❌ การเชื่อมต่อล้มเหลว: " . $conn->connect_error);
}
mysqli_set_charset($conn, "utf8");

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reservation_id = $_POST['reservation_id'];

    $sql = "SELECT package_id FROM getting_table WHERE reservation_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $reservation_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($package_id);
        $stmt->fetch();
        header("Location: menu_selac.php?reservation_id=" . $reservation_id . "&package_id=" . $package_id);
        exit();
    } else {
        $error_message = "❌ รหัสการจองไม่ถูกต้อง!";
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
    <link rel="stylesheet" href="../CSS/style.css">
</head>
<body>
    <div class="container">
        <h2>กรอกรหัสการจอง</h2>
        <form method="POST">
            <input type="number" name="reservation_id" placeholder="รหัสการจอง" required>
            <button type="submit">ตรวจสอบ</button>
        </form>
        <?php if (!empty($error_message)) { ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php } ?>
    </div>
</body>
</html>
