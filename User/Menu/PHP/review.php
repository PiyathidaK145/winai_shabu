<?php
// เชื่อมต่อฐานข้อมูล
$servername = "localhost";
$username = "root";
$password = "123456";
$dbname = "a_shabu";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// ตรวจสอบค่าที่รับมา
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (!isset($_POST["receipt_id"]) || !isset($_POST["review"])) {
      die("<p style='color:red;'>Missing required fields!</p>");
  }

  $receipt_id = isset($_POST['receipt_id']) ? intval($_POST['receipt_id']) : 0;  // แปลงเป็น int
  if ($receipt_id == 0) {
      die("<p style='color:red;'>Invalid receipt_id!</p>");
  }

  $comment_text = $conn->real_escape_string($_POST["review"]);

  // รับค่า tag ต่าง ๆ และกำหนดค่าเริ่มต้นเป็น 0 ถ้าไม่ได้รับมา
  $tag_food_id = isset($_POST["tag_food_id"]) ? intval($_POST["tag_food_id"]) : 0;
  $tag_clean_id = isset($_POST["tag_clean_id"]) ? intval($_POST["tag_clean_id"]) : 0;
  $tag_price_id = isset($_POST["tag_price_id"]) ? intval($_POST["tag_price_id"]) : 0;
  $tag_service_id = isset($_POST["tag_service_id"]) ? intval($_POST["tag_service_id"]) : 0;
  $tag_other_id = isset($_POST["tag_other_id"]) ? intval($_POST["tag_other_id"]) : 0;

  // ตรวจสอบค่าที่รับมา
  echo "<pre>";
  print_r($_POST);
  echo "</pre>";
  // exit(); // ใช้ตอน debug เพื่อดูค่าที่ส่งมา

  // ใช้ prepared statement
  $stmt = $conn->prepare("INSERT INTO review (receipt_id, comment_text, tag_food_id, tag_clean_id, tag_price_id, tag_service_id, tag_other_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
  if ($stmt === false) {
      die("Error preparing the SQL statement: " . $conn->error);
  }

  $stmt->bind_param("isiiiii", $receipt_id, $comment_text, $tag_food_id, $tag_clean_id, $tag_price_id, $tag_service_id, $tag_other_id);

  if ($stmt->execute()) {
      echo "<p style='color:green;'>Data inserted successfully!</p>";
      header("Location: thankyou.php");
      exit();
  } else {
      echo "<p style='color:red;'>Error: " . $stmt->error . "</p>";
  }

  $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="th">

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

    <!-- การให้คะแนนด้วยดาว -->
    <div class="stars">
      <span data-value="1">★</span>
      <span data-value="2">★</span>
      <span data-value="3">★</span>
      <span data-value="4">★</span>
      <span data-value="5">★</span>
    </div>
    <p class="rating-message"></p>

    <!-- แสดงแท็กที่เลือกตามคะแนน -->
    <div class="tags-container">
      <div class="tags" id="tags-container"></div>
    </div>

    <!-- ช่องกรอกรีวิว -->
    <form id="review-form" method="POST" action="review.php">
      <textarea name="review" placeholder="เขียนรีวิวหลังใช้บริการ"></textarea>
      <input type="hidden" name="tag_food_id">
      <input type="hidden" name="tag_clean_id">
      <input type="hidden" name="tag_price_id">
      <input type="hidden" name="tag_service_id">
      <input type="hidden" name="tag_other_id">
      <input type="hidden" id="receipt_id" name="receipt_id" value="<?php echo htmlspecialchars($receipt_id); ?>">
      <button id="submit-btn" type="submit">ยืนยัน</button>
    </form>
  </div>

  <script src="scriptreview.js"></script>
</body>

</html>

