<?php
include '../../../config/connect_db.php';

header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST["receipt_id"]) || !isset($_POST["review"]) || empty($_POST["receipt_id"])) {
        die("<p style='color:red;'>❌ Missing required fields!</p>");
    }

    $receipt_id = intval($_POST["receipt_id"]);
    $review_text = $conn->real_escape_string($_POST["review"]);
    $rating = isset($_POST["rating"]) ? intval($_POST["rating"]) : 0;

    // รับค่า tag ID ต่าง ๆ (ใช้ NULL แทน 0)
    $tag_food_id = !empty($_POST["tag_food_id"]) ? intval($_POST["tag_food_id"]) : NULL;
    $tag_clean_id = !empty($_POST["tag_clean_id"]) ? intval($_POST["tag_clean_id"]) : NULL;
    $tag_price_id = !empty($_POST["tag_price_id"]) ? intval($_POST["tag_price_id"]) : NULL;
    $tag_service_id = !empty($_POST["tag_service_id"]) ? intval($_POST["tag_service_id"]) : NULL;
    $tag_other_id = !empty($_POST["tag_other_id"]) ? intval($_POST["tag_other_id"]) : NULL;

    // ตรวจสอบว่า receipt_id มีอยู่จริงหรือไม่
    $result = $conn->query("SELECT receipt_id FROM receipt WHERE receipt_id = $receipt_id");
    if ($result->num_rows == 0) {
        die("<p style='color:red;'>❌ Error: receipt_id ไม่ถูกต้อง</p>");
    }

    // เตรียมคำสั่ง SQL
    $stmt = $conn->prepare("INSERT INTO review (receipt_id, comment_text, tag_food_id, tag_clean_id, tag_price_id, tag_service_id, tag_other_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
    
    if ($stmt === false) {
        die("❌ Error preparing the SQL statement: " . $conn->error);
    }

    $stmt->bind_param("isiiiii", $receipt_id, $review_text, $tag_food_id, $tag_clean_id, $tag_price_id, $tag_service_id, $tag_other_id);

    if (!$stmt->execute()) {
        die("<p style='color:red;'>❌ SQL Error: " . $stmt->error . "</p>");
    } else {
        echo "<p style='color:green;'>✅ บันทึกรีวิวสำเร็จ!</p>";
        header("Location: thankyou.php");
        exit();
    }

    // ปิด statement (ตรวจสอบก่อนเรียก close)
    if (isset($stmt) && $stmt !== false) {
        $stmt->close();
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="th">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../../CSS/stylesreview.css">
  <title>Restaurant Review</title>
</head>

<body>
  <div class="review-container">
    <h1>Winai's Shabu</h1>
    <h2>รีวิวร้านอาหาร</h2>

    <div class="stars">
      <span data-value="1">★</span>
      <span data-value="2">★</span>
      <span data-value="3">★</span>
      <span data-value="4">★</span>
      <span data-value="5">★</span>
    </div>
    <p class="rating-message"></p>

    <div class="tags-container">
      <div class="tags" id="tags-container"></div>
    </div>

    <form id="review-form" method="POST" action="review.php">
      <textarea name="review" placeholder="เขียนรีวิวหลังใช้บริการ"></textarea>
      <input type="hidden" name="tag_food_id">
      <input type="hidden" name="tag_clean_id">
      <input type="hidden" name="tag_price_id">
      <input type="hidden" name="tag_service_id">
      <input type="hidden" name="tag_other_id">
      <input type="hidden" id="receipt_id" name="receipt_id">
      <button id="submit-btn" type="submit">ยืนยัน</button>
    </form>
  </div>

  <script src="../../Javascript/scriptreview.js"></script>
</body>

</html>
