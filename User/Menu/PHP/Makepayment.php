<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>หน้ารอการชำระเงิน</title>
    <link rel="stylesheet" href="Makepayment.css">
</head>

<body>
    <div class="container">
        <div class="circle-wrapper">
            <svg class="progress-circle" width="100" height="100">
                <circle class="circle-bg" cx="50" cy="50" r="40" />
                <circle class="circle-progress" cx="50" cy="50" r="40" />
            </svg>
            <div class="checkmark hidden">&#10004;</div>
        </div>
        <p class="success-text">กำลังตรวจสอบการชำระเงิน...</p>
        <p class="description">กรุณารอสักครู่ ข้อมูลของคุณกำลังอยู่ในระหว่างการตรวจสอบจากทางร้าน</p>
    </div>

    <script src="Makepayment.js"></script>
</body>
</html>
