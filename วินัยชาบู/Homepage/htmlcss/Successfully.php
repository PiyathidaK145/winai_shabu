<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ทำรายการสำเร็จ</title>
    <link rel="stylesheet" href="Dec_Successfully.css">
</head>

<body>
    <div class="success-box">
        <button class="close-btn" onclick="closeBox()">&times;</button>
        <h1>ทำรายการสำเร็จ</h1>

        
        <div class="table-info">
            <p>รหัสการจอง</p>
            <p id="booking-id">
                <?php echo isset($_GET['reservation_id']) ? htmlspecialchars($_GET['reservation_id']) : 'N/A'; ?>
            </p>
        </div>

        <div class="table-info">
            <p>หมายเลขโต๊ะ</p>
            <p id="table-number">
                <?php echo isset($_GET['table']) ? htmlspecialchars($_GET['table']) : 'N/A'; ?>
            </p>
        </div>

        <div class="table-info">
            <p>เวลา</p>
            <p id="time">
                <?php echo isset($_GET['time']) ? htmlspecialchars($_GET['time']) : 'N/A'; ?>
            </p>
        </div>

        <div class="note">
            <p>ควรมาก่อนเวลา 15 นาที</p>
            <p>และโปรดแคปหน้าจอเพื่อแสดงต่อพนักงาน</p>
        </div>
    </div>

    <script>
        // ฟังก์ชันเมื่อกดปุ่มปิด
        function closeBox() {
            // กลับไปที่หน้าแรก
            window.location.href = "Homepage.php"; // เปลี่ยนเป็น path ของหน้าแรก
        }
    </script>

</body>

</html>
