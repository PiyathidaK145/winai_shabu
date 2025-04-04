<?php
include '../../config/connect_db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo "<script>console.log('POST Data: " . json_encode($_POST) . "');</script>";
    $table_id = $_POST['table_id'];
    $time_id = $_POST['time_id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $number_of_guest = $_POST['number_of_guest'];

    if (!$table_id || !$time_id || !$first_name || !$last_name || !$number_of_guest) {
        echo "<script>alert('กรุณากรอกข้อมูลให้ครบถ้วน'); history.back();</script>";
        exit;
    }

    $first_name = mysqli_real_escape_string($conn, $first_name);
    $last_name = mysqli_real_escape_string($conn, $last_name);

    // ตรวจสอบว่าลูกค้าอยู่ในระบบหรือไม่
    $check_sql = "SELECT customer_id FROM customer WHERE first_name = '$first_name' AND last_name = '$last_name'";
    $check_result = mysqli_query($conn, $check_sql);

    if (mysqli_num_rows($check_result) > 0) {
        $row = mysqli_fetch_assoc($check_result);
        $customer_id = $row['customer_id'];
    } else {
        echo "<script>alert('ไม่พบลูกค้าในระบบ'); history.back();</script>";
        exit;
    }

    // 1. อัปเดตสถานะโต๊ะเป็น 'Busy'
    $update_sql = "UPDATE table_availability 
                   SET status = 'Busy',
                       `last_update` = CURRENT_TIMESTAMP
                   WHERE table_id = ? AND time_id = ?";
    $stmt_update = $conn->prepare($update_sql);
    $stmt_update->bind_param("ii", $table_id, $time_id);
    $stmt_update->execute();
    $stmt_update->close();

    // 2. ดึง availability_id ของโต๊ะและช่วงเวลา
    $select_sql = "SELECT availability_id 
                   FROM table_availability
                   WHERE table_id = ? AND time_id = ?";
    $stmt_select = $conn->prepare($select_sql);
    $stmt_select->bind_param("ii", $table_id, $time_id);
    $stmt_select->execute();
    $result = $stmt_select->get_result();

    if ($row = $result->fetch_assoc()) {
        $availability_id = $row['availability_id'];

        // 3. บันทึกข้อมูล Walk-in โดยใช้ customer_id แทนชื่อ
        $insert_sql = "INSERT INTO walkin (customer_id, number_of_guest, availability_id) 
                       VALUES (?, ?, ?)";
        $stmt_insert = $conn->prepare($insert_sql);
        $stmt_insert->bind_param("iii", $customer_id, $number_of_guest, $availability_id);

        if ($stmt_insert->execute()) {
            echo "<script>alert('บันทึกข้อมูล Walk-in สำเร็จ'); window.location.href='index.php';</script>";
        } else {
            echo "<script>alert('เกิดข้อผิดพลาดในการบันทึก: " . $conn->error . "'); history.back();</script>";
        }

        $stmt_insert->close();
    } else {
        echo "<script>alert('ไม่พบข้อมูล availability_id ของโต๊ะนี้ในช่วงเวลาที่เลือก'); history.back();</script>";
    }

    $stmt_select->close();
    $conn->close();
} else {
    echo "<script>alert('วิธีการเข้าถึงไม่ถูกต้อง'); history.back();</script>";
}
