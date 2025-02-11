<?php


// Default table number (can be set dynamically too)
$table_number = 3;

// If the form is submitted, capture the order time from POST data
$order_time = isset($_POST['order_time']) ? $_POST['order_time'] : 'Not yet submitted';
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Display</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="Displayeditems.css">
    <script>
        function setOrderTime() {
            // Get the current time in HH:MM format
            const currentTime = new Date().toLocaleTimeString('th-TH', { hour: '2-digit', minute: '2-digit' });
            // Set the hidden input value with the current time
            document.getElementById("order_time").value = currentTime;
        }
    </script>
</head>
<body>
    <div class="order-container">
        <div class="order-header">
            <h1>โต๊ะ <?php echo $table_number; ?></h1>
            <p>เวลา <?php echo $order_time; ?></p>
        </div>
        <form method="POST" onsubmit="setOrderTime()">
            <input type="hidden" id="order_time" name="order_time">
            <table class="order-table">
                <thead>
                    <tr>
                        <th>ชื่อเมนู</th>
                        <th>จำนวน</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($order as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                            <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <button type="submit">สั่งออเดอร์</button>
        </form>
    </div>
</body>
</html>
