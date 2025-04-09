<?php
session_start();
$base_url = "http://localhost/a_shabu/client";
include dirname(__FILE__) . '/config/connect_db.php';

$username = mysqli_real_escape_string($conn, $_POST['username']); // email หรือ employee_id
$password = mysqli_real_escape_string($conn, $_POST['password']);

// ดึงข้อมูลจาก employee โดยใช้ email (หรือ employee_id ก็ได้ ถ้ากำหนดให้ล็อกอินด้วยรหัส)
$query = mysqli_query($conn, "SELECT * FROM employee WHERE email = '$username'") or die('query failed');

if (mysqli_num_rows($query) === 1) {
    $user = mysqli_fetch_assoc($query);

    // เทียบ password (กรณียังไม่เข้ารหัส)
    if ($password === $user['phone']) { // ตัวอย่างใช้ phone เป็น password (ควรเปลี่ยนในระบบจริง)
        $_SESSION['login'] = true;
        $_SESSION['employee_id'] = $user['employee_id'];
        $_SESSION['name'] = $user['first_name'] . ' ' . $user['last_name'];
        $_SESSION['role_id'] = $user['role_id'];

        // นำ role_id ไปเลือกเส้นทางให้เหมาะสม
        switch ($user['role_id']) {
            case 202: // พนักงานเสิร์ฟ
                header("Location: {$base_url}/Staff_Waiter/table_order_list.php");
                break;
            case 204: // พนักงานคลังวัตถุดิบ
                header("Location: {$base_url}/Staff_Warehouse/index.php");
                break;
            case 205: // พนักงานครัว
                header("Location: {$base_url}/Staff_Kitchen/table_order_list.php");
                break;
            case 206: // พนักงานเคาน์เตอร์
                header("Location: {$base_url}/Staff_Counter/index.php");
                break;
            case 207: // ผู้จัดการ
                header("Location: {$base_url}/Staff_Manager/home.php");
                break;
            case 208: // ผู้บริหาร
                header("Location: {$base_url}/Staff_Executive/home.php");
                break;
            default:
                $_SESSION['message'] = "บทบาทผู้ใช้งานไม่ถูกต้อง";
                header("Location: login.php");
                exit();
        }
        exit();
    }
}

// ล็อกอินไม่สำเร็จ
$_SESSION['message'] = "อีเมลหรือรหัสผ่านไม่ถูกต้อง";
header("Location: login.php");
exit();
?>
