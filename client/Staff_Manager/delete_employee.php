<?php
// เริ่มต้นเซสชันเพื่อเก็บข้อมูล
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_GET['ajax'])) {
    include 'include/header.php';
}

include dirname(__FILE__) . '/../../config/connect_db.php';

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
$employeeId = $_GET['id'];

try {
    // สร้างการเชื่อมต่อฐานข้อมูล
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // ลบข้อมูลพนักงานจากฐานข้อมูล
    $stmt = $conn->prepare("DELETE FROM employee WHERE employee_id = :employee_id");
    $stmt->bindParam(':employee_id', $employeeId, PDO::PARAM_INT);
    $stmt->execute();

    // หลังจากลบแล้ว แสดงข้อความแจ้งเตือนและรีไดเรกต์ไปยังหน้ารายชื่อพนักงาน
    $_SESSION['message'] = "Employee deleted successfully!";
    header("Location: employee.php");
    exit;
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
<<<<<<< HEAD
?>
=======
?>
>>>>>>> 8b2216fd18008dad437930077b67c9ef256e13d2
