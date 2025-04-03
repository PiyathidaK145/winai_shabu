<?php
include dirname(__FILE__) . '/../../config/connect_db.php';

$reservation_id = $_GET['id'] ?? '';

if (!$reservation_id) {
    echo json_encode(['status' => 'error', 'message' => 'Missing ID']);
    exit;
}

$sql = "SELECT r.reservation_id, r.first_name, r.last_name, r.number_of_guest, a.table_id, a.time_id
        FROM reservation r
        INNER JOIN table_availability a ON r.availability_id = a.availability_id
        WHERE r.reservation_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $reservation_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // ✅ Map time_id เป็นช่วงเวลา
    function getTimeSlotRange($time_id) {
        $map = [
            1001 => '16-18',
            1002 => '18-20',
            1003 => '20-22',
            1004 => '22-00',
            1005 => '00-02'
        ];
        return $map[$time_id] ?? 'ไม่ทราบช่วงเวลา';
    }

    $time_slot = getTimeSlotRange($row['time_id']);

    echo json_encode([
        'status' => 'found',
        'reservation_id' => $row['reservation_id'],
        'first_name' => $row['first_name'],
        'last_name' => $row['last_name'],
        'number_of_guest' => $row['number_of_guest'],
        'table_id' => $row['table_id'],
        'time_slot' => $time_slot
    ]);
} else {
    echo json_encode(['status' => 'not_found']);
}

?>

