<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include dirname(__FILE__) . '/../../config/connect_db.php';
include dirname(__FILE__) . '/include/header.php';

function parseEnumValues($enum) {
    preg_match_all("/'([^']+)'/", $enum, $matches);
    return $matches[1];
}

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $getEnum = function($column) use ($conn) {
        $stmt = $conn->prepare("SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'promotion_item' AND COLUMN_NAME = :column");
        $stmt->execute(['column' => $column]);
        return parseEnumValues($stmt->fetch(PDO::FETCH_ASSOC)['COLUMN_TYPE']);
    };

    $for_customer_type_values = $getEnum('for_customer_type');
    $discount_type_values = $getEnum('discount_type');
    $status_values = $getEnum('status');
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // รับค่าจากฟอร์ม
    $title = $_POST['title'] ?? null;
    $description = $_POST['description']  ?? null;
    $for_customer_type = $_POST['for_customer_type'] ?? null;
    $start_date = $_POST['start_date']?? null;
    $end_date = $_POST['end_date']?? null;
    $menu_ids = $_POST['menu_ids'] ?? [];
    $discount_type = $_POST['discount_type']?? null;
    $discount_value = $_POST['discount_value'] ?? null;
    $pay_people = $_POST['pay_people'] ?? null;
    $status = $_POST['status']?? null;
    $quantity = $_POST['quantity'] ?? null;
    $created_at = date('Y-m-d H:i:s')?? null;

    // อัปโหลดรูปภาพหลัก
    $image_url = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $uploadDirectory = 'uploads/';
        if (!is_dir($uploadDirectory)) {
            mkdir($uploadDirectory, 0777, true);
        }

        $imageName = basename($_FILES['image']['name']);
        $uploadFilePath = $uploadDirectory . uniqid() . '_' . $imageName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFilePath)) {
            $image_url = $uploadFilePath;
        }
    }

    try {
        // 1. บันทึกข้อมูลลงตาราง promotion
        $stmt = $conn->prepare("INSERT INTO promotion (promotions_name, description, image_url) VALUES (?, ?, ?)");
        $stmt->execute([$title, $description, $image_url]);
        $promotion_id = $conn->lastInsertId();

        // 2. บันทึกเมนูที่เลือกลง promotion_item
        $stmtItem = $conn->prepare("INSERT INTO promotion_item 
        (promotion_id, menu_id, for_customer_type, discount_type, discount_value, pay_people, start_date, end_date, status, created_at, quantity) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        // ถ้าไม่มีเมนูเลือก ให้บันทึกเป็น NULL สำหรับ menu_id
    if (empty($menu_ids)) {
        $menu_ids = [null]; // ให้มีการบันทึก record แม้ไม่มีเมนู
    }

        foreach ($menu_ids as $menu_id) {
            $stmtItem->execute([
                $promotion_id,
                $menu_id,
                $for_customer_type,
                $discount_type,
                $discount_value,
                $pay_people,
                $start_date,
                $end_date,
                $status,
                $created_at,
                $quantity,
            ]);
        }

        echo "<script>alert('เพิ่มโปรโมชั่นสำเร็จ!'); window.location.href = 'Promotion.php';</script>";
    } catch (PDOException $e) {
        echo "เกิดข้อผิดพลาด: " . $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มโปรโมชั่น</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body,
        html {
            height: 100%;
        }

        .container-fluid {
            height: 100%;
            overflow-y: auto;
        }

        .form-container {
            max-height: 80vh;
            /* Adjust height for better usability */
            overflow-y: auto;
        }
    </style>
</head>

<body>

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
                            <!-- ฟิลด์สำหรับประเภทลูกค้า -->
                            <div class="mb-3">
                                <label for="for_customer_type" class="form-label">ประเภทลูกค้า</label>
                                <select name="for_customer_type" id="for_customer_type" class="form-control" required>
                                    <?php
                                    // แสดงตัวเลือกประเภทลูกค้าจาก enum
                                    foreach ($for_customer_type_values as $value) {
                                        echo '<option value="' . $value . '">' . ucfirst($value) . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <!-- ฟิลด์สำหรับคำอธิบายโปรโมชัน -->
                            <div class="mb-3">
                                <label for="description" class="form-label">คำอธิบาย</label>
                                <textarea name="description" id="description" class="form-control" rows="4" placeholder="Description" ></textarea>
                            </div>

                            <!-- ฟิลด์สำหรับวันที่เริ่มต้นและวันที่สิ้นสุด -->
                            <div class="mb-3">
                                <label for="start_date" class="form-label">วันเริ่มต้นโปรโมชั่น</label>
                                <input type="datetime-local" name="start_date" id="start_date" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="end_date" class="form-label">วันสิ้นสุดโปรโมชั่น</label>
                                <input type="datetime-local" name="end_date" id="end_date" class="form-control" required>
                            </div>

                            <!-- ฟิลด์สำหรับการอัปโหลดรูปภาพ -->
                            <div class="mb-3">
                                <label for="image" class="form-label">อัปโหลดรูปภาพโปรโมชั่น</label>
                                <input type="file" name="image" id="image" class="form-control" accept="image/*">
                            </div>
                            <!-- ฟิลด์สำหรับการเลือกหมวดหมู่และเมนู -->
                            <div class="mb-3">
                                <label for="category_menus" class="form-label">เลือกเมนูสำหรับโปรโมชั่น</label>
                                <div id="category_menus">
                                    <?php
                                    $stmt = $conn->prepare("SELECT category_id, category_name FROM category");
                                    $stmt->execute();
                                    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                    foreach ($categories as $category) {
                                        // ดึงรายการเมนูจากแต่ละหมวดหมู่
                                        $stmt = $conn->prepare("SELECT m.menu_id, r.item_name FROM menu m JOIN raw_material r ON m.raw_material_id = r.raw_material_id WHERE r.category_id = ?");

                                        $stmt->execute([$category['category_id']]);
                                        $menus = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                    ?>
                                        <div class="category-section">
                                            <h5><?php echo htmlspecialchars($category['category_name']); ?></h5>
                                            <div>
                                                <?php foreach ($menus as $menu) { ?>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="menu_ids[]" value="<?php echo $menu['menu_id']; ?>" id="menu_<?php echo $menu['menu_id']; ?>">
                                                        <label class="form-check-label" for="menu_<?php echo $menu['menu_id']; ?>">
                                                            <?php echo htmlspecialchars($menu['item_name']); ?>
                                                        </label>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <!-- ฟิลด์สำหรับประเภทส่วนลด -->
                            <div class="mb-3">
                                <label for="discount_type" class="form-label">ประเภทส่วนลด</label>
                                <select name="discount_type" id="discount_type" class="form-control" required>
                                    <?php
                                    // แสดงตัวเลือกประเภทส่วนลดจาก enum
                                    foreach ($discount_type_values as $value) {
                                        echo '<option value="' . $value . '">' . ucfirst($value) . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <!-- ฟิลด์สำหรับมูลค่าของส่วนลด -->
                            <div class="mb-3">
                                <label for="discount_value" class="form-label">มูลค่าของส่วนลด</label>
                                <input type="number" name="discount_value" id="discount_value" class="form-control" >
                            </div>

                            <!-- ฟิลด์สำหรับจำนวนคนที่ต้องการจ่าย -->
                            <div class="mb-3" id="pay_people_field" style="display: none;">
                                <label for="pay_people" class="form-label">จำนวนคนที่ต้องจ่าย (กรณีมาด้วยกันแต่จ่ายน้อยกว่า จำนวนที่มา เช่น มา 3 จ่าย 2ดังนั้นในคอลัมนี้ต้องลงจำนวนเป็น 2)</label>
                                <input type="number" name="pay_people" id="pay_people" class="form-control" >
                            </div>

                            <!-- ฟิลด์สำหรับสถานะ -->
                            <div class="mb-3">
                                <label for="status" class="form-label">สถานะโปรโมชั่น</label>
                                <select name="status" id="status" class="form-control" required>
                                    <?php
                                    // แสดงตัวเลือกสถานะจาก enum
                                    foreach ($status_values as $value) {
                                        echo '<option value="' . $value . '">' . ucfirst($value) . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <!-- ฟิลด์สำหรับจำนวนสินค้า -->
                            <div class="mb-3">
                                <label for="quantity" class="form-label">จำนวนโปรโมชั่น</label>
                                <input type="number" name="quantity" id="quantity" class="form-control" >
                            </div>

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

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

    <script>
        // ตรวจสอบการเปลี่ยนแปลงค่าใน select
        document.getElementById('discount_type').addEventListener('change', function() {
            // ตรวจสอบว่าเลือกประเภท "count_number" หรือไม่
            const payPeopleField = document.getElementById('pay_people_field');
            if (this.value === 'count_number') {
                // แสดงฟิลด์สำหรับจำนวนคนที่ต้องจ่าย
                payPeopleField.style.display = 'block';
            } else {
                // ซ่อนฟิลด์สำหรับจำนวนคนที่ต้องจ่าย
                payPeopleField.style.display = 'none';
            }
        });
    </script>

</body>

</html>