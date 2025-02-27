<?php
// ตรวจสอบว่ามีการส่งฟอร์มหรือไม่
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // รับข้อมูลจากฟอร์ม
    $title = $_POST['title'];
    $description = $_POST['description'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // การอัปโหลดรูปภาพ
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_name = $_FILES['image']['name'];
        $image_destination = "uploads/" . basename($image_name);
        
        // ตรวจสอบและอัปโหลดไฟล์
        if (move_uploaded_file($image_tmp_name, $image_destination)) {
            echo "<p><strong>Image uploaded successfully:</strong> " . htmlspecialchars($image_name) . "</p>";
        } else {
            echo "<p><strong>Failed to upload image.</strong></p>";
        }
    }

    // บันทึกข้อมูล หรือทำการประมวลผลที่ต้องการ (สามารถบันทึกลงในฐานข้อมูลหรือไฟล์ได้)
    echo "<h2>Promotion Added</h2>";
    echo "<p><strong>Title:</strong> " . htmlspecialchars($title) . "</p>";
    echo "<p><strong>Description:</strong> " . htmlspecialchars($description) . "</p>";
    echo "<p><strong>Start Date:</strong> " . htmlspecialchars($start_date) . "</p>";
    echo "<p><strong>End Date:</strong> " . htmlspecialchars($end_date) . "</p>";
}
?>

<?php include 'include/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <main class="main-wrapper col-md-9 ms-sm-auto py-4 col-lg-9 px-md-4 border-start">
            <div class="container">
                <h1>เพิ่มโปรโมชั่น</h1>
                <div class="form-container">
                    <form action="add_promotion.php" method="POST" enctype="multipart/form-data">
                        <!-- ฟิลด์สำหรับชื่อโปรโมชัน -->
                        <div class="mb-3">
                            <label for="title" class="form-label">ชื่อโปรโมชั่น</label>
                            <input type="text" name="title" id="title" class="form-control" placeholder="Promotion Title" required>
                        </div>

                        <!-- ฟิลด์สำหรับคำอธิบายโปรโมชัน -->
                        <div class="mb-3">
                            <label for="description" class="form-label">คำอธิบาย</label>
                            <textarea name="description" id="description" class="form-control" rows="4" placeholder="Description" required></textarea>
                        </div>

                        <!-- ฟิลด์สำหรับวันที่เริ่มต้นและวันที่สิ้นสุด -->
                        <div class="mb-3">
                            <label for="start_date" class="form-label">วันเริ่มต้น</label>
                            <input type="datetime-local" name="start_date" id="start_date" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="end_date" class="form-label">วันสิ้นสุด</label>
                            <input type="datetime-local" name="end_date" id="end_date" class="form-control" required>
                        </div>

                        <!-- ฟิลด์สำหรับการอัปโหลดรูปภาพ -->
                        <div class="mb-3">
                            <label for="image" class="form-label">อัปโหลดรูปภาพ</label>
                            <input type="file" name="image" id="image" class="form-control" accept="image/*">
                        </div>

                        <!-- ปุ่มการบันทึก -->
                        <div class="form-buttons">
                            <a href="Promotion.php" class="btn btn-secondary btn-back">กลับ</a>
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
