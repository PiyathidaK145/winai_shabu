<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="rule.css">
    <title>กฎการจอง</title>
</head>

<body>
    <div class="modal">
        <div class="modal-header">
            <h2><?php echo "กฎการจอง"; ?></h2>
        </div>
        <div class="modal-content">
            <ol>
                <?php
                // รายการกฎการจอง
                $rules = [
                    "หากมาสายเกิน 15 นาที โต๊ะจะถูกปล่อยให้ลูกค้าท่านอื่น",
                    "ระบบเปิดใช้งานตลอดเวลา แต่สามารถเริ่มทำการจองโต๊ะได้เฉพาะในช่วงเวลา 16:00-00:00",
                    "การยกเลิกการจอง ควรแจ้งล่วงหน้าก่อนเวลาที่จองอย่างน้อย 30 นาที",
                    "กรุณาแคปหน้าจอเมื่อจองสำเร็จเพื่อแสดงหลักฐานการยืนยันกับทางร้าน"
                ];

                // วนลูปแสดงรายการ
                foreach ($rules as $rule) {
                    echo "<li>$rule</li>";
                }
                ?>
            </ol>
        </div>
        <button class="confirm-button" onclick="goBack()"><?php echo "ยืนยัน"; ?></button>
    </div>

    <script>
        // ฟังก์ชันสำหรับกลับไปหน้าก่อนหน้า
        function goBack() {
            history.back();
        }
    </script>
</body>

</html>

