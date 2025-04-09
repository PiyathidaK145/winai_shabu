<?php
// แสดง error เพื่อ debug
error_reporting(E_ALL);
ini_set('display_errors', 1);

// เชื่อมต่อฐานข้อมูล
include dirname(__FILE__) . '/../../config/connect_db.php';

// สร้างการเชื่อมต่อ PDO
try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// ตรวจสอบว่ามีการส่ง id ของ promotion item มาไหม
if (isset($_GET['id'])) {
    $promotion_item_id = $_GET['id'];

    // ดึงข้อมูล promotion_item เพื่อเช็คก่อนว่าอยู่จริงไหม และใช้ promotion_id เพื่อลบ promotion
    $stmt = $pdo->prepare("SELECT * FROM promotion_item WHERE promotion_item_id = ?");
    $stmt->execute([$promotion_item_id]);
    $promotion_item = $stmt->fetch();

    if ($promotion_item) {
        $promotion_id = $promotion_item['promotion_id'];

        // ลบจากตาราง promotion ก่อน
        $delete_promotion_stmt = $pdo->prepare("DELETE FROM promotion WHERE promotion_id = ?");
        $delete_promotion_stmt->execute([$promotion_id]);

        // ลบรูปภาพหากมี
        $image_path = "uploads/" . basename($promotion_item['image_url']);
        if (!empty($promotion_item['image_url']) && file_exists($image_path)) {
            unlink($image_path);
        }

        // ลบจาก promotion_item ทีหลัง
        $delete_item_stmt = $pdo->prepare("DELETE FROM promotion_item WHERE promotion_item_id = ?");
        $delete_item_stmt->execute([$promotion_item_id]);

        // เปลี่ยนเส้นทางไปยังหน้า Promotion.php
        header("Location: Promotion.php");
        exit;
    } else {
        echo "ไม่พบโปรโมชั่นนี้ในระบบ";
    }
} else {
    echo "ไม่พบข้อมูลที่ส่งมา";
}
<<<<<<< HEAD
?>
=======
?>
>>>>>>> 8b2216fd18008dad437930077b67c9ef256e13d2
