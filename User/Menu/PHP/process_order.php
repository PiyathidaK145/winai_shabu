<?php
// เชื่อมต่อฐานข้อมูล
$servername = "localhost";
$username = "root";
$password = "12345678";
$dbname = "winaishabu";


try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // รับข้อมูลจาก AJAX
    $orderList = json_decode(file_get_contents('php://input'), true);
    
    // เริ่มการทำงานในฐานข้อมูล
    $pdo->beginTransaction();
    
    foreach ($orderList as $orderItem) {
        // ตรวจสอบชื่อเมนูในตาราง menu_item และค้นหาค่า item_id
        $stmt = $pdo->prepare("SELECT item_id FROM menu_item WHERE item_name = :item_name");
        $stmt->execute(['item_name' => $orderItem['menuName']]);
        $menu = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($menu) {
            // หากพบ item_id ก็เพิ่มข้อมูลลงใน selecting_item
            $stmt = $pdo->prepare("INSERT INTO selecting_item (selecting_menu_id, selecting_item_name, quanity, created_at) 
                                   VALUES (:selecting_menu_id, :selecting_item_name, :quanity, NOW())");
            $stmt->execute([
                'selecting_menu_id' => $menu['item_id'],
                'selecting_item_name' => $orderItem['menuName'],
                'quanity' => $orderItem['quantity']
            ]);
        } else {
            // ถ้าไม่พบเมนูในตาราง menu_item
            echo json_encode(['success' => false, 'message' => 'ไม่พบเมนู: ' . $orderItem['menuName']]);
            exit;
        }
    }

    // หากไม่มีข้อผิดพลาดก็ commit ข้อมูล
    $pdo->commit();

    // Redirect ไปที่หน้า Displayeditems.php
    header('Location: Displayeditems.php');
    exit;

} catch (PDOException $e) {
    // หากมีข้อผิดพลาดให้ rollback การทำงาน
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
