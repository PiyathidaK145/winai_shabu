<?php
header('Content-Type: application/json');
include __DIR__ . '/../config/connect_db.php';

// ตรวจสอบว่ามีการเชื่อมต่อฐานข้อมูลจริงหรือไม่
if (!$conn) {
    echo json_encode(["error" => "Database connection not established"]);
    exit();
}

//  รับค่าช่วงวันที่
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : null;
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : null;


//  คำนวณยอดขายและจำนวนลูกค้า
$sales_sql = "
    SELECT 
        SUM(p.total_payment) AS total_sales,
        COUNT(DISTINCT pv.payment_id) AS total_customers
    FROM payment_verificatio pv
    JOIN payment p ON pv.payment_id = p.payment_id
    WHERE pv.approve = 'completed'";

if ($start_date && $end_date) {
    $sales_sql .= " AND p.payment_timestamp BETWEEN ? AND ?";
}

$sales_stmt = $conn->prepare($sales_sql);
if (!$sales_stmt) {
    echo json_encode(["error" => "SQL Prepare Failed: " . $conn->error]);
    exit();
}

if ($start_date && $end_date) {
    $sales_stmt->bind_param("ss", $start_date, $end_date);
}

$sales_stmt->execute();
$sales_result = $sales_stmt->get_result();
$sales_data = $sales_result->fetch_assoc();
$total_sales = $sales_data['total_sales'] ?? 0;
$total_customers = $sales_data['total_customers'] ?? 0;
$sales_stmt->close();

// คำนวณต้นทุนรวม
$cost_sql = "
    SELECT ROUND(SUM((rm.quanity / m.quantity_of_sale) * rm.price_of_cost)) AS total_cost
    FROM raw_material rm
    JOIN menu m ON rm.raw_material_id = m.raw_material_id;";

$cost_stmt = $conn->prepare($cost_sql);
if (!$cost_stmt) {
    echo json_encode(["error" => "SQL Prepare Failed: " . $conn->error]);
    exit();
}

$cost_stmt->execute();
$cost_result = $cost_stmt->get_result();
if ($cost_result) {
    $cost_data = $cost_result->fetch_assoc();
    $total_cost = $cost_data['total_cost'] ?? 0;
} else {
    $total_cost = 0;
}
$cost_stmt->close();

// คำนวณกำไร (Profit)
$total_profit = $total_sales - $total_cost;

// ส่งข้อมูลเป็น JSON
echo json_encode([
    "total_sales" => $total_sales,
    "total_customers" => $total_customers,
    "total_cost" => $total_cost,
    "total_profit" => $total_profit
], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

$conn->close();
