<?php
include dirname(__FILE__) . '/../../config/connect_db.php';
date_default_timezone_set("Asia/Bangkok");

// ประกาศตัวแปรวัน/เวลา
$today_date = date('Y-m-d');
$now = date('Y-m-d H:i:s');

// ฟังก์ชันใช้ตรวจสอบและบันทึกการแจ้งเตือน
function notify_if_not_exists($conn, $role_id, $msg, $created_at)
{
    $escaped_msg = mysqli_real_escape_string($conn, $msg);
    $check = mysqli_query($conn, "SELECT 1 FROM notification 
        WHERE role_id = $role_id 
        AND DATE(created_at) = '" . date('Y-m-d') . "' 
        AND Notification_name = '$escaped_msg'");

    if (mysqli_num_rows($check) == 0) {
        mysqli_query($conn, "INSERT INTO notification 
            (getting_table_id, role_id, Notification_name, status, created_at)
            VALUES (NULL, $role_id, '$escaped_msg', 'unread', '$created_at')");
    }
}

// 🔔 แจ้งเตือนวัตถุดิบใกล้หมด
$low_stock_sql = "
    SELECT crm.calculate_raw_material_id, rm.item_name, crm.capacity
    FROM calculate_raw_material crm
    JOIN import_raw_material irm ON crm.import_raw_material_id = irm.import_raw_material_id
    JOIN menu m ON irm.menu_id = m.menu_id
    JOIN raw_material rm ON m.raw_material_id = rm.raw_material_id
    WHERE crm.capacity <= 10
";
$low_stock_result = mysqli_query($conn, $low_stock_sql);

while ($row = mysqli_fetch_assoc($low_stock_result)) {
    $msg = "วัตถุดิบใกล้หมด: {$row['item_name']} เหลือ {$row['capacity']} หน่วย";
    notify_if_not_exists($conn, 204, $msg, $now);
}

// 🔔 แจ้งเตือนวัตถุดิบใกล้หมดอายุ
$exp_sql = "SELECT calculate_raw_material_id, expried_date FROM calculate_raw_material";
$exp_result = mysqli_query($conn, $exp_sql);
$now_dt = new DateTime();

while ($row = mysqli_fetch_assoc($exp_result)) {
    $id = $row['calculate_raw_material_id'];
    $exp = new DateTime($row['expried_date']);
    $diff = $now_dt->diff($exp)->format('%r%a');

    if ($diff <= 2 && $diff >= 0) {
        $get_name_sql = "
    SELECT rm.item_name 
    FROM calculate_raw_material crm
    JOIN import_raw_material irm ON crm.import_raw_material_id = irm.import_raw_material_id
    JOIN menu m ON irm.menu_id = m.menu_id
    JOIN raw_material rm ON m.raw_material_id = rm.raw_material_id
    WHERE crm.calculate_raw_material_id = $id
    LIMIT 1
";
        $name_result = mysqli_query($conn, $get_name_sql);
        $name_row = mysqli_fetch_assoc($name_result);
        $item_name = $name_row['item_name'] ?? 'ไม่ทราบชื่อ';

        $msg = "วัตถุดิบใกล้หมดอายุภายใน $diff วัน: $item_name";
        notify_if_not_exists($conn, 204, $msg, $now);
    }
}
