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


// ดึงข้อมูลจากตาราง raw_material
$sql = "SELECT * FROM raw_material";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>เมนูอาหาร</title>
</head>
<body>
    <h2>รายการเมนู</h2>
    <table border="1">
        <tr>
            <th>ชื่อเมนู</th>
            <th>รูปภาพ</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row["name"]; ?></td>
            <td><img src="uploads/<?php echo $row["image_path"]; ?>" width="100"></td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>

<?php
$conn->close();
?>
