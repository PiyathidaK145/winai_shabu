<?php
include '../../../config/connect_db.php';

header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST["receipt_id"]) || !isset($_POST["review"]) || empty($_POST["receipt_id"])) {
        die("<p style='color:red;'>❌ Missing required fields!</p>");
    }
    if (isset($_GET['rating'])) {
      header('Content-Type: application/json');
      $rating = intval($_GET['rating']);
    
      function getTagsByRating($conn, $table, $rating, $idField, $nameField)
      {
        $sql = "SELECT $idField, $nameField FROM $table WHERE rating = ? ORDER BY RAND() LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $rating);
        $stmt->execute();
        $result = $stmt->get_result();
    
        return $result->num_rows > 0 ? $result->fetch_assoc() : null;
      }
    
      $response = [
        "message" => "คุณให้คะแนน $rating ดาว",
        "tags" => [
          "tag_clean" => getTagsByRating($conn, 'tag_clean', $rating, 'tag_clean_id', 'tag_name'),
          "tag_food" => getTagsByRating($conn, 'tag_food', $rating, 'tag_food_id', 'tag_name'),
          "tag_price" => getTagsByRating($conn, 'tag_price', $rating, 'tag_price_id', 'tag_name'),
          "tag_service" => getTagsByRating($conn, 'tag_service', $rating, 'tag_service_id', 'tag_name'),
          "tag_other" => getTagsByRating($conn, 'tag_other', $rating, 'tag_other_id', 'tag_name')
        ]
      ];
    
      echo json_encode($response);
      exit;
    }
    
    // ❗หากไม่มีการขอ JSON จะมาแสดงหน้า HTML ปกติแทน
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      // รับข้อมูลจากฟอร์ม
      $review = $_POST['review'];
      $tag_food_id = $_POST['tag_food_id'];
      $tag_clean_id = $_POST['tag_clean_id'];
      $tag_price_id = $_POST['tag_price_id'];
      $tag_service_id = $_POST['tag_service_id'];
      $tag_other_id = $_POST['tag_other_id'];
      $rating = $_POST['rating'];  // รับค่า rating
      $receipt_id = $_POST['receipt_id'];
    
      // ตรวจสอบว่าไม่มีค่าที่ขาดหายไป
      if (empty($review) || empty($receipt_id)) {
        echo "กรุณากรอกข้อมูลให้ครบถ้วน";
        exit;
      }
    
      // สร้างคำสั่ง SQL เพื่อบันทึกข้อมูล
      $sql = "INSERT INTO review (receipt_id, comment_text, rating_selac, tag_food_id, tag_clean_id, tag_price_id, tag_service_id, tag_other_id)
              VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    
      // เตรียมคำสั่ง SQL
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("isiiiiii", $receipt_id, $review, $rating, $tag_food_id, $tag_clean_id, $tag_price_id, $tag_service_id, $tag_other_id);
    
      // ตรวจสอบการทำงานของคำสั่ง SQL
      if ($stmt->execute()) {
        echo "บันทึกรีวิวสำเร็จ";
      } else {
        echo "เกิดข้อผิดพลาดในการบันทึกข้อมูล: " . $stmt->error;
      }
    
      // ปิดการเชื่อมต่อฐานข้อมูล
      $stmt->close();
    }
    
    $conn->close();}
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

    <div class="stars">
      <span class="star" data-value="1">★</span>
      <span class="star" data-value="2">★</span>
      <span class="star" data-value="3">★</span>
      <span class="star" data-value="4">★</span>
      <span class="star" data-value="5">★</span>
    </div>
    <p id="feedback-text" class="rating-message">กรุณาเลือกดาวเพื่อดูคำแนะนำ</p>

    <div class="tags-container">
      <div class="tags" id="tags-container"></div>
    </div>

    <form id="review-form" method="POST" action="review.php">
      <textarea name="review" placeholder="เขียนรีวิวหลังใช้บริการ" required></textarea>
      <input type="hidden" name="tag_food_id">
      <input type="hidden" name="tag_clean_id">
      <input type="hidden" name="tag_price_id">
      <input type="hidden" name="tag_service_id">
      <input type="hidden" name="tag_other_id">
      <input type="hidden" name="rating"> <!-- เพิ่ม input สำหรับ rating -->
      <input type="hidden" id="receipt_id" name="receipt_id"
        value="<?php echo isset($_GET['receipt_id']) ? htmlspecialchars($_GET['receipt_id']) : ''; ?>">
      <button id="submit-btn" type="submit">ยืนยัน</button>
    </form>

  </div>

  <script src="scriptreview.js"></script>
</body>

</html>
