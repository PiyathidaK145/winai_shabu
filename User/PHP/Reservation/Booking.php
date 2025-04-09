<?php
include '../../../config/connect_db.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['booking_data'])) {
        echo "<script>alert('ไม่มีข้อมูลการจอง!'); window.location.href='../Home/Homepage.php';</script>";
        exit();
    }

    $booking = $_SESSION['booking_data'];

    $first_name = isset($booking['first_name']) ? $booking['first_name'] : '';
    $last_name = isset($booking['last_name']) ? $booking['last_name'] : '';
    $number_of_guest = isset($booking['number_of_guest']) ? intval($booking['number_of_guest']) : 0;
    $availability_id = isset($booking['availability_id']) ? intval($booking['availability_id']) : 0;
    $status = "Confirm"; // ✅ แก้ไขการสะกดให้ถูกต้อง

    if (empty($first_name) || empty($last_name) || $availability_id == 0) {
        echo "<script>alert('ข้อมูลการจองไม่ถูกต้อง!'); window.history.back();</script>";
        exit();
    }

    // 🔍 ค้นหา custumer_id จากตาราง custumer
    $stmt = $conn->prepare("SELECT custumer_id FROM custumer WHERE first_name = ? AND last_name = ? LIMIT 1");
    if ($stmt) {
        $stmt->bind_param("ss", $first_name, $last_name);
        $stmt->execute();
        $stmt->bind_result($custumer_id);
        $stmt->fetch();
        $stmt->close();

        // ถ้าไม่พบข้อมูลลูกค้า
        if (!$custumer_id) {
            echo "<script>alert('ไม่พบข้อมูลลูกค้าในระบบ!'); window.history.back();</script>";
            exit();
        }
    } else {
        die("❌ Error preparing statement (custumer check): " . $conn->error);
    }

    // ✅ บันทึกข้อมูลลงในตาราง Reservation โดยใช้ custumer_id ที่ค้นพบ
    $stmt = $conn->prepare("INSERT INTO Reservation (custumer_id, status, time_update, availability_id, number_of_guest) VALUES (?, ?, CURRENT_TIMESTAMP, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("isii", $custumer_id, $status, $availability_id, $number_of_guest);

        if ($stmt->execute()) {
            $reservation_id = $stmt->insert_id;

            // ✅ อัปเดตสถานะของ table_availability เป็น 'Busy' ถ้าการจองสำเร็จ
            $update_stmt = $conn->prepare("UPDATE table_availability SET status = 'Busy' WHERE availability_id = ?");
            if ($update_stmt) {
                $update_stmt->bind_param("i", $availability_id);
                $update_stmt->execute();
                $update_stmt->close();
            }

            header("Location: Successfully.php?reservation_id=$reservation_id&availability_id=$availability_id&table=" . $booking['table'] . "&time=" . $booking['time']);
            exit();
        } else {
            echo "<script>alert('เกิดข้อผิดพลาดในการจอง โปรดลองอีกครั้ง'); window.history.back();</script>";
        }
        $stmt->close();
    } else {
        die("❌ Error preparing statement (insert reservation): " . $conn->error);
    }
}
$conn->close();
?>
