<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ</title>
    <link rel="stylesheet" href="stylesLogin.css">
</head>
<body>
    <div class="login-container">
        <h2>เข้าสู่ระบบ</h2>

        <?php 
        // แสดงข้อความแสดงข้อผิดพลาดถ้ามี
        if (isset($_GET['error'])): 
        ?>
            <p style="color: red;"><?php echo htmlspecialchars($_GET['error']); ?></p>
        <?php endif; ?>

        <form action="login_process.php" method="POST">
            <!-- ช่องกรอกชื่อและนามสกุล -->
            <input type="text" name="first_name" placeholder="ชื่อ" required>
            <input type="text" name="last_name" placeholder="นามสกุล" required>
            <button type="submit">เข้าสู่ระบบ</button>
        </form>
    </div>
</body>
</html>