<?php
include '../../config/connect_db.php';

header('Content-Type: application/json');

if (isset($_GET['table_id']) && isset($_GET['time'])) {
    $table_id = intval($_GET['table_id']);
    $time = $_GET['time'];

    // Mapping time slot เป็น time_id
    $time_map = [
        '16-18' => 1001,
        '18-20' => 1002,
        '20-22' => 1003,
        '22-00' => 1004,
        '00-02' => 1005
    ];
    $time_id = $time_map[$time] ?? 1001;

    // ตรวจสอบจาก Walk-in
    $walkin_sql = "SELECT w.first_name, w.last_name, w.number_of_guest, 'walkin' AS type
                   FROM walkin w
                   INNER JOIN table_availability a ON w.availability_id = a.availability_id
                   WHERE a.table_id = $table_id AND a.time_id = $time_id
                   LIMIT 1";
    $walkin_result = mysqli_query($conn, $walkin_sql);

    if ($walkin_data = mysqli_fetch_assoc($walkin_result)) {
        echo json_encode([
            'type' => 'walkin',
            'first_name' => $walkin_data['first_name'],
            'last_name' => $walkin_data['last_name'],
            'number_of_guest' => $walkin_data['number_of_guest']
        ]);
        exit;
    }

    // ตรวจสอบจาก Reservation
    $reserve_sql = "SELECT r.first_name, r.last_name, r.number_of_guest, 'reservation' AS type
                    FROM reservation r
                    INNER JOIN table_availability a ON r.availability_id = a.availability_id
                    WHERE a.table_id = $table_id AND a.time_id = $time_id
                    LIMIT 1";
    $reserve_result = mysqli_query($conn, $reserve_sql);

    if ($reserve_data = mysqli_fetch_assoc($reserve_result)) {
        echo json_encode([
            'type' => 'reservation',
            'first_name' => $reserve_data['first_name'],
            'last_name' => $reserve_data['last_name'],
            'number_of_guest' => $reserve_data['number_of_guest']
        ]);
        exit;
    }

    // ไม่พบข้อมูล
    http_response_code(404);
    echo json_encode(['error' => 'ไม่พบข้อมูลการจอง']);
} else {
    http_response_code(400);
    echo json_encode(['error' => 'พารามิเตอร์ไม่ครบ']);
}
?>
