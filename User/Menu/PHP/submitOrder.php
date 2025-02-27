<?php
header("Content-Type: application/json");

// ข้อมูลเชื่อมต่อฐานข้อมูล
$host = "localhost";
$user = "root";
$password = "123456";
$database = "a_shabu";

// สร้างการเชื่อมต่อ
$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "⛔ การเชื่อมต่อล้มเหลว: " . $conn->connect_error]));
}
mysqli_set_charset($conn, "utf8");

// รับข้อมูลจาก Frontend
$data = json_decode(file_get_contents("php://input"), true);

// Debug JSON ที่ได้รับจาก Frontend
$json_input = file_get_contents("php://input");
error_log("🔹 JSON ที่รับมา: " . $json_input);
$data = json_decode($json_input, true);


// ตรวจสอบข้อมูลที่ได้รับ
if (!isset($data["reservation_id"]) || !isset($data["items"]) || empty($data["items"])) {
    echo json_encode(["success" => false, "message" => "⚠ ข้อมูลไม่ครบถ้วน"]);
    exit;
}

$reservation_id = $data["reservation_id"];
$items = $data["items"];

// 🔹 ค้นหา `getting_table_id` จาก `reservation_id`
$query = "SELECT getting_table_id FROM getting_table WHERE reservation_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $reservation_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows == 0) {
    echo json_encode(["success" => false, "message" => "⚠ ไม่พบโต๊ะสำหรับรหัสการจองนี้"]);
    exit;
}
$row = $result->fetch_assoc();
$getting_table_id = $row["getting_table_id"];

// 🔹 วนลูปบันทึกข้อมูลออเดอร์
foreach ($items as $item_id => $item) {
    $quantity = $item["quantity"];

    // 🔹 ค้นหา `menu_id` โดยใช้ `raw_material`
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
        echo json_encode(["success" => false, "message" => "⚠ ไม่พบเมนูสำหรับวัตถุดิบที่เลือก"]);
        exit;
    }
    
    $row = $result->fetch_assoc();
    $menu_id = $row["menu_id"];

    // 🔹 บันทึกข้อมูลลงในตาราง `order`
    $query = "
        INSERT INTO `order` (menu_id, getting_table_id, quantity, order_date, status) 
        VALUES (?, ?, ?, NOW(), 'in_process')";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iii", $menu_id, $getting_table_id, $quantity);

    if (!$stmt->execute()) {
        echo json_encode(["success" => false, "message" => "⛔ เกิดข้อผิดพลาดในการบันทึกออเดอร์"]);
        exit;
    }
}

// ปิดการเชื่อมต่อ
$stmt->close();
$conn->close();

// ✅ ส่งข้อความสำเร็จ
echo json_encode(["success" => true, "message" => "✅ บันทึกออเดอร์เรียบร้อย"]);
?>
