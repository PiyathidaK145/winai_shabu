<?php
// เชื่อมต่อฐานข้อมูล
$servername = "localhost";
$username = "root";
$password = "123456";
$dbname = "winaishabu";

// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ตรวจสอบการส่งฟอร์ม
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $review = $_POST['review'];
    
    // เก็บข้อมูลรีวิวลงฐานข้อมูล
    $sql = "INSERT INTO reviews (review_text) VALUES (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $review);
    
    if ($stmt->execute()) {
        echo "Review submitted successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="stylesreview.css">
  <title>Restaurant Review</title>
</head>
<body>
  <div class="review-container">
    <h1>Winai's Shabu</h1>
    <h2>รีวิวร้านอาหาร</h2>

    <!-- Stars -->
    <div class="stars">
      <?php
      // ค่าเริ่มต้นของการให้คะแนน
      $rating = 0; // ค่าตัวอย่าง

      for ($i = 1; $i <= 5; $i++) {
          echo '<span data-value="' . $i . '"' . ($i <= $rating ? ' class="selected"' : '') . '>★</span>';
      }
      ?>
    </div>
    <p class="rating-message"></p>

    <!-- Recommended Tags -->
    <div class="tags-container">
      <div class="tags">
        <?php
        // ตัวอย่างการดึงข้อมูลแท็กจากฐานข้อมูล
        //$tags = ['อร่อย', 'บรรยากาศดี', 'คุ้มค่า']; // ค่าตัวอย่าง
        foreach ($tags as $tag) {
            echo '<span class="tag">' . htmlspecialchars($tag) . '</span>';
        }
        ?>
      </div>
    </div>

    <!-- Review Box -->
    <form method="POST" action="">
      <textarea name="review" placeholder="เขียนรีวิวหลังใช้บริการ"></textarea>
      <button id="submit-btn" type="submit">ยืนยัน</button>
    </form>
  </div>

  <script src="scriptreview.js"></script>
</body>
</html>
