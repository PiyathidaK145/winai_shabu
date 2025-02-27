<?php
// เริ่มต้นเซสชันเพื่อเก็บข้อมูล
session_start();

// ตรวจสอบว่ามีอาร์เรย์สำหรับเก็บข้อมูลพนักงานหรือไม่ ถ้าไม่มีให้สร้าง
if (!isset($_SESSION['staff_list'])) {
    $_SESSION['staff_list'] = [];
}

// ตรวจสอบการส่งฟอร์ม
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // รับข้อมูลจากฟอร์ม
    $name = htmlspecialchars($_POST['name']);
    $position = htmlspecialchars($_POST['position']);
    $email = htmlspecialchars($_POST['email']);
    $user = htmlspecialchars($_POST['user']);
    $password = htmlspecialchars($_POST['password']);
    $phone = htmlspecialchars($_POST['phone']);

    // เพิ่มข้อมูลพนักงานในอาร์เรย์เซสชัน
    $_SESSION['staff_list'][] = [
        'name' => $name,
        'position' => $position,
        'email' => $email,
        'user' => $user,
        'password' => $password,
        'phone' => $phone
    ];

    // แสดงข้อความยืนยันการเพิ่มพนักงาน
    echo "<div class='alert alert-success' role='alert'>New staff has been added successfully!</div>";

    // กลับไปยังหน้ารายการพนักงาน
    header("Location: staf.php");
    exit;
}
?>

<?php include 'include/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <main class="main-wrapper col-md-9 ms-sm-auto py-4 col-lg-9 px-md-4 border-start">
            <div class="container">
                <h1 class="text-center">แก้ไข</h1>
                <div class="form-container">
                    <form method="POST">
                        <div class="mb-3">
                            <label for="name" class="form-label">ชื่อ-นามสกุล</label>
                            <input type="text" name="name" class="form-control" id="name" placeholder="Enter name" required>
                        </div>
                        <div class="mb-3">
                            <label for="position" class="form-label">หน้าที่</label>
                            <select name="position" class="form-select" id="position" required>
                                <option value="">Select Position</option>
                                <option value="Cleaning Staff">พนักงานทำความสะอาด</option>
                                <option value="Receptionist">พนักงานต้อนรับ</option>
                                <option value="Waiter">พนักงานเสิร์ฟ</option>
                                <option value="Counter Staff">พนักงานเคาท์เตอร์</option>
                                <option value="Kitchen Staff">พนักงานครัว</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">อีเมล</label>
                            <input type="email" name="email" class="form-control" id="email" placeholder="Enter email" required>
                        </div>
                        <div class="mb-3">
                            <label for="user" class="form-label">Username</label>
                            <input type="text" name="user" class="form-control" id="user" placeholder="Enter username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" id="password" placeholder="Enter password" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">เบอร์โทร</label>
                            <input type="tel" name="phone" class="form-control" id="phone" placeholder="Enter phone number" required>
                        </div>

                        <!-- แบ่งปุ่มออกเป็น 2 ปุ่มที่อยู่ในแถวเดียวกัน -->
                        <div class="form-buttons">
                            <a href="Staff.php" class="btn btn-secondary">กลับ</a>
                            <button type="submit" class="btn btn-primary">บันทึกข้อมูล</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
