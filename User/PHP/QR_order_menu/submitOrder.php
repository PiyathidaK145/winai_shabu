<?php
header("Content-Type: application/json");

include '../../../config/connect_db.php';
mysqli_set_charset($conn, "utf8");

header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
ob_clean();


// Handle preflight request
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(204);
    exit;
}

// รับข้อมูลจาก Frontend
$json_input = file_get_contents("php://input");
error_log("🔹 JSON ที่รับมา: " . $json_input);
$data = json_decode($json_input, true);

// ตรวจสอบข้อมูลที่ได้รับ
if (!isset($data["getting_table_id"]) || !isset($data["items"]) || empty($data["items"])) {
    echo json_encode(["success" => false, "message" => "⚠ ข้อมูลไม่ครบถ้วน"]);
    exit;
}

$getting_table_id = intval($data["getting_table_id"]);
$items = $data["items"];

// ตรวจสอบว่า getting_table_id มีอยู่ในระบบหรือไม่
$query = "SELECT getting_table_id FROM getting_table WHERE getting_table_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $getting_table_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows == 0) {
    echo json_encode(["success" => false, "message" => "⚠ ไม่พบโต๊ะนี้ในระบบ"]);
    exit;
}

// 🔹 วนลูปบันทึกข้อมูลออเดอร์
foreach ($items as $item_id => $item) {
    $quantity = intval($item["quantity"]);

    // 🔹 ค้นหา menu_id โดยใช้ raw_material_id
    $query = "
        SELECT menu.menu_id 
        FROM raw_material 
        JOIN menu ON raw_material.raw_material_id = menu.raw_material_id 
        WHERE raw_material.raw_material_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        echo json_encode(["success" => false, "message" => "⚠ ไม่พบเมนูสำหรับวัตถุดิบ ID: $item_id"]);
        exit;
    }

    $row = $result->fetch_assoc();
    $menu_id = $row["menu_id"];

    // 🔹 บันทึกข้อมูลลงในตาราง order
    $query = "
        INSERT INTO `order` (menu_id, getting_table_id, quantity, order_date, status_kitchen, status_waiter) 
        VALUES (?, ?, ?, NOW(), 'in_progress', 'pending')";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iii", $menu_id, $getting_table_id, $quantity);

    if (!$stmt->execute()) {
        echo json_encode(["success" => false, "message" => "⛔ เกิดข้อผิดพลาดในการบันทึกออเดอร์"]);
        exit;
    }
}

// ✅ สำเร็จ
$stmt->close();
$conn->close();

echo json_encode(["success" => true, "message" => "✅ บันทึกออเดอร์เรียบร้อย"]);
?>
