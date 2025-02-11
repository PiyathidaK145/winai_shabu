<?php
$host = "localhost";
$user = "root";
$password = "123456";
$database = "winaishabu";

// เชื่อมต่อฐานข้อมูล
$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("การเชื่อมต่อล้มเหลว: " . $conn->connect_error);
}

// ฟังก์ชันสร้าง getting_table_id แบบสุ่ม 6 หลัก
function generateUniqueId($conn)
{
    do {
        $uniqueId = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $sql = "SELECT COUNT(*) AS count FROM getting_table WHERE getting_table_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $uniqueId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
    } while ($row['count'] > 0);
    return $uniqueId;
}

// ตรวจสอบว่ามีการส่งข้อมูลผ่าน POST หรือไม่
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reservation_id = $_POST["reservation_id"];
    $package_id = $_POST["package_id"];
    $promotion_id = isset($_POST['promotion_id']) && $_POST['promotion_id'] !== "" ? $_POST['promotion_id'] : NULL;
    $employee_id = isset($_POST['employee_id']) && $_POST['employee_id'] !== "" ? $_POST['employee_id'] : NULL;
    $getting_table_id = generateUniqueId($conn); // สร้างรหัส 6 หลักโดยอัตโนมัติ

    // คำนวณราคาทั้งหมด
    $total_amount = 0;

    // ดึงราคาของแพ็กเกจ
    $sql = "SELECT price FROM package WHERE package_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $package_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $total_amount = $row["price"];
    }
    $stmt->close();

    // ตรวจสอบว่า promotion_id มีอยู่จริงในฐานข้อมูลหรือไม่
    if (!empty($promotion_id)) {
        $sql = "SELECT discount_type, discount_value FROM promotion WHERE promotion_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $promotion_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($row["discount_type"] == "persentage") {
                $total_amount -= ($total_amount * $row["discount_value"] / 100);
            } else {
                $total_amount -= $row["discount_value"];
            }
        } else {
            $promotion_id = NULL; // ถ้าไม่มีโปรโมชันที่ตรงกัน ให้ใช้ค่า NULL
        }
        $stmt->close();
    }

    // บันทึกข้อมูลลง getting_table
    $sql = "INSERT INTO getting_table (getting_table_id, reservation_id, employee_id, package_id, promotion_id, total_amount) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siiidi", $getting_table_id, $reservation_id, $employee_id, $package_id, $promotion_id, $total_amount);

    if ($stmt->execute()) {
        echo "<script>alert('บันทึกข้อมูลสำเร็จ!'); window.location.href='assign_table.php';</script>";
        exit(); // หยุดการทำงานของสคริปต์หลังจาก redirect
    } else {
        echo "<script>alert('เกิดข้อผิดพลาดในการบันทึกข้อมูล: " . $stmt->error . "'); window.history.back();</script>";
    }

    $stmt->close();
}

$conn->close();
?>