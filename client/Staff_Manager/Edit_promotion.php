<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include dirname(__FILE__) . '/../../config/connect_db.php';
include dirname(__FILE__) . '/include/header.php';

function parseEnumValues($enum)
{
    preg_match_all("/'([^']+)'/", $enum, $matches);
    return $matches[1];
}

function getMenuItemsGroupedByCategory($conn)
{
    // ใช้การจอยระหว่าง raw_material และ category เพื่อดึง category_name
    $stmt = $conn->prepare("
        SELECT c.category_name, rm.item_name, rm.raw_material_id
        FROM raw_material rm
        JOIN category c ON rm.category_id = c.category_id
        ORDER BY c.category_name, rm.item_name
    ");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


// รับค่าจาก URL
$promotion_item_id = $_GET['promotion_item_id'] ?? null;
if (!$promotion_item_id) {
    die("ไม่พบโปรโมชั่นที่ต้องการแก้ไข");
}

// เชื่อมต่อกับฐานข้อมูล
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// ค้นหาข้อมูล promotion_id จาก promotion_item
$stmt = $conn->prepare("
    SELECT promotion_id FROM promotion_item WHERE promotion_item_id = :promotion_item_id
");
$stmt->execute(['promotion_item_id' => $promotion_item_id]);
$promotion_item = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$promotion_item) {
    die("ไม่พบข้อมูลของโปรโมชั่นไอเทม");
}

// ใช้ promotion_id ที่ได้จาก promotion_item ไปดึงข้อมูลจากตาราง promotion
$promotion_id = $promotion_item['promotion_id'];
$stmt = $conn->prepare("
    SELECT p.promotions_name, p.description, p.image_url,
           pi.for_customer_type, pi.discount_type, pi.discount_value, 
           pi.pay_people, pi.start_date, pi.end_date, pi.status, pi.quantity,
           GROUP_CONCAT(pi.menu_id) as menu_ids
    FROM promotion p
    JOIN promotion_item pi ON p.promotion_id = pi.promotion_id
    WHERE p.promotion_id = :promotion_id
    GROUP BY p.promotion_id
");
$stmt->execute(['promotion_id' => $promotion_id]);
$promotion = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$promotion) {
    die("ไม่พบโปรโมชั่น");
}

// ข้อมูลที่ได้จะใช้ในฟอร์มของคุณ
$menusGroupedByCategory = getMenuItemsGroupedByCategory($conn);
if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
    $imageTmpName = $_FILES['image']['tmp_name'];
    $imageName = basename($_FILES['image']['name']);
    $imagePath = "uploads/" . $imageName; // กำหนดเส้นทางในการบันทึกภาพ

    // ตรวจสอบประเภทไฟล์ (เลือกทำได้)
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $fileType = mime_content_type($imageTmpName);
    if (in_array($fileType, $allowedTypes)) {
        // ย้ายไฟล์ที่อัปโหลดไปยังโฟลเดอร์ที่ต้องการ
        move_uploaded_file($imageTmpName, $imagePath);

        // อัพเดทฐานข้อมูลด้วย image_url ใหม่
        $stmt = $conn->prepare("UPDATE promotion SET image_url = :image_url WHERE promotion_id = :promotion_id");
        $stmt->execute(['image_url' => $imagePath, 'promotion_id' => $promotion_id]);
    } else {
        echo "ประเภทไฟล์ไม่ถูกต้อง!";
    }
} else {
    // ถ้าไม่มีการอัปโหลดใหม่ ใช้ภาพเดิมจากฐานข้อมูล
    $imagePath = $promotion['image_url'];  // ใช้ภาพเดิม
}

// ดึงค่าของ enum
$for_customer_type_values = getEnumValues($conn, 'promotion_item', 'for_customer_type');
$discount_type_values = getEnumValues($conn, 'promotion_item', 'discount_type');
$status_values = getEnumValues($conn, 'promotion_item', 'status');

// ฟังก์ชันในการดึงค่า enum จากฐานข้อมูล
function getEnumValues($conn, $table, $column)
{
    $stmt = $conn->prepare("SHOW COLUMNS FROM $table LIKE :column");
    $stmt->execute(['column' => $column]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // ค้นหาค่า ENUM ในแถวที่ได้จากฐานข้อมูล
    preg_match("/^enum\(\'(.*)\'\)$/", $row['Type'], $matches);
    if (isset($matches[1])) {
        return explode("','", $matches[1]);
    }
    return [];
}
$startDate = new DateTime($promotion['start_date']);
$endDate = new DateTime($promotion['end_date']);
$menu_ids = $_POST['menu_ids'] ?? [];

// ตรวจสอบว่า 'menu_ids' มีการส่งมาในฟอร์มหรือไม่
if (isset($_POST['menu_ids']) && !empty($_POST['menu_ids'])) {
    $menu_ids = $_POST['menu_ids'];
} else {
    $menu_ids = NULL; // กำหนดให้เป็น null หากไม่มีการเลือกเมนู
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        // เริ่ม transaction
        $conn->beginTransaction();

        // อัปเดตตาราง promotion
        $stmt = $conn->prepare("
        UPDATE promotion 
            SET promotions_name = :title,
            description = :description,
            image_url = :image_url
            WHERE promotion_id = :promotion_id
        ");
        $stmt->execute([
            'title' => $_POST['title'],
            'description' => $_POST['description'],
            'image_url' => $imagePath,
            'promotion_id' => $promotion_id
        ]);

        // ลบข้อมูล promotion_item เดิม
        $stmt = $conn->prepare("DELETE FROM promotion_item WHERE promotion_id = :promotion_id");
        $stmt->execute(['promotion_id' => $promotion_id]);

        // เตรียมข้อมูลใหม่จากฟอร์ม
        $menu_ids = $_POST['menu_ids'] ?? []; // กำหนดให้เป็น array ว่างหากไม่มีค่า
        $for_customer_type = $_POST['for_customer_type'] ?? null;
        $discount_type = $_POST['discount_type'] ?? null;
        $discount_value = $_POST['discount_value'] ?? null;
        $pay_people = $_POST['pay_people'] ?? null;
        $start_date = $_POST['start_date'] ?? null;
        $end_date = $_POST['end_date'] ?? null;
        $status = $_POST['status'] ?? null;
        $quantity = $_POST['quantity'] ?? null;

        if (!empty($menu_ids) && is_array($menu_ids)) {
            foreach ($menu_ids as $raw_id) {
                // ดึง menu_id ที่ใช้ raw_material_id นี้จากตาราง menu
                $stmt = $conn->prepare("
                    SELECT DISTINCT md.menu_id
                    FROM menu md
                    JOIN raw_material m ON md.raw_material_id = m.raw_material_id
                    WHERE m.raw_material_id IN (" . implode(',', array_fill(0, count($menu_ids), '?')) . ")
                ");
                $stmt->execute($menu_ids);  // binding ของอาร์เรย์ menu_ids โดยตรง

                $menu_ids_for_raw_material = $stmt->fetchAll(PDO::FETCH_COLUMN);  // ดึงค่า menu_id ที่เกี่ยวข้องกับ raw_material_id

                // บันทึกข้อมูลลงใน promotion_item สำหรับแต่ละ menu_id
                foreach ($menu_ids_for_raw_material as $menu_id) {
                    // ตรวจสอบว่า promotion_id และ menu_id นี้มีอยู่ใน promotion_item หรือยัง
                    $checkStmt = $conn->prepare("
                        SELECT COUNT(*) FROM promotion_item 
                        WHERE promotion_id = :promotion_id AND menu_id = :menu_id
                    ");
                    $checkStmt->execute([
                        'promotion_id' => $promotion_id,
                        'menu_id' => $menu_id
                    ]);
                    
                    // ถ้าไม่มีข้อมูลใน promotion_item, ให้บันทึกข้อมูล
                    if ($checkStmt->fetchColumn() == 0) {
                        $stmt = $conn->prepare("
                            INSERT INTO promotion_item (
                                promotion_id, menu_id, for_customer_type, discount_type,
                                discount_value, pay_people, start_date, end_date,
                                status, quantity
                            ) VALUES (
                                :promotion_id, :menu_id, :for_customer_type, :discount_type,
                                :discount_value, :pay_people, :start_date, :end_date,
                                :status, :quantity
                            )
                        ");
                        $stmt->execute([
                            'promotion_id' => $promotion_id,
                            'menu_id' => $menu_id,
                            'for_customer_type' => $for_customer_type ?: null,
                            'discount_type' => $discount_type ?: null,
                            'discount_value' => is_numeric($discount_value) ? $discount_value : null,
                            'pay_people' => is_numeric($pay_people) ? $pay_people : null,
                            'start_date' => $start_date ?: null,
                            'end_date' => $end_date ?: null,
                            'status' => $status ?: null,
                            'quantity' => is_numeric($quantity) ? $quantity : null,
                        ]);
                    }
                }
            }
        } else {
            // Handle case where menu_ids is not set or is empty
            echo "No menu items selected!";
        }

        // commit ถ้าทุกอย่างผ่าน
        $conn->commit();

        echo "<script>alert('บันทึกข้อมูลเรียบร้อยแล้ว'); window.location.href='Promotion.php';</script>";
        exit;
    } catch (PDOException $e) {
        $conn->rollBack();
        echo "เกิดข้อผิดพลาด: " . $e->getMessage();
    }
}

?>
<script>
    window.onload = function() {
        // ตรวจสอบค่าเริ่มต้นของ discount_type และแสดง/ซ่อน pay_people
        togglePayPeopleField(document.getElementById('discount_type').value);
        togglePercentLabel(); // ตรวจสอบสถานะเริ่มต้นของ percentLabel

        // เมื่อค่าใน discount_type เปลี่ยน
        document.getElementById('discount_type').addEventListener('change', function() {
            togglePayPeopleField(this.value); // ตรวจสอบ pay_people เมื่อ discount_type เปลี่ยน
            togglePercentLabel(); // ตรวจสอบ percentLabel เมื่อ discount_type เปลี่ยน
        });
    };

    // ฟังก์ชั่นในการแสดง/ซ่อน pay_people
    function togglePayPeopleField(discountType) {
        var payPeopleField = document.getElementById('pay_people_field');
        if (discountType === 'count_number') {
            payPeopleField.style.display = 'block'; // แสดงฟิลด์ pay_people
        } else {
            payPeopleField.style.display = 'none'; // ซ่อนฟิลด์ pay_people
        }
    }

    // ฟังก์ชั่นในการแสดง/ซ่อน percentLabel
    function togglePercentLabel() {
        const type = document.getElementById('discount_type').value;
        const percentLabel = document.getElementById('percentLabel');
        percentLabel.style.display = (type === 'percentage') ? 'inline' : 'none';
    }
</script>

<body>
    <div class="container-fluid">
        <div class="row">
            <main class="main-wrapper col-md-9 ms-sm-auto py-4 col-lg-9 px-md-4 border-start">
                <div class="container mt-5">
                    <form method="POST" enctype="multipart/form-data">
                        <h2 class="mb-4">แก้ไขโปรโมชั่น</h2>
                        <div class="mb-3">
                            <label for="title" class="form-label">ชื่อโปรโมชั่น</label>
                            <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($promotion['promotions_name']); ?>" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">รายละเอียดโปรโมชั่น</label>
                            <textarea name="description" id="description" class="form-control" rows="4" required><?php echo htmlspecialchars($promotion['description']); ?></textarea>
                        </div>
                        <div class="mb-3">
                            <div class="mb-3">
                                <label for="image" class="form-label">ภาพโปรโมชั่น</label>

                                <!-- ตรวจสอบว่ามีภาพเดิมในฐานข้อมูลหรือไม่ -->
                                <?php
                                if (isset($promotion['image_url']) && !empty($promotion['image_url'])) {
                                    // แสดงภาพเก่าจากฐานข้อมูล
                                    echo "<img src='" . htmlspecialchars($promotion['image_url']) . "' alt='Current Image' style='max-width: 100px;' class='mt-2'>";
                                } else {
                                    echo "ไม่มีภาพโปรโมชั่นเดิม";
                                }
                                ?>

                                <!-- ฟอร์มอัปโหลดภาพใหม่ -->
                                <input type="file" name="image" id="image" class="form-control mt-2">
                            </div>

                            <!-- วันที่เริ่มต้น -->
                            <div class="mb-3">
                                <label for="start_date" class="form-label">วันที่เริ่มต้น</label>
                                <input type="datetime-local" name="start_date" id="start_date" value="<?php echo $startDate->format('Y-m-d\TH:i'); ?>" class="form-control" required>
                            </div>

                            <!-- วันที่สิ้นสุด -->
                            <div class="mb-3">
                                <label for="end_date" class="form-label">วันที่สิ้นสุด</label>
                                <input type="datetime-local" name="end_date" id="end_date" value="<?php echo $endDate->format('Y-m-d\TH:i'); ?>" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="for_customer_type" class="form-label">ประเภทลูกค้า</label>
                                <select name="for_customer_type" id="for_customer_type" class="form-select" required>
                                    <?php foreach ($for_customer_type_values as $value): ?>
                                        <option value="<?php echo $value; ?>" <?php echo $value == $promotion['for_customer_type'] ? 'selected' : ''; ?>>
                                            <?php echo $value; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="discount_type" class="form-label">ประเภทส่วนลด</label>
                                <select name="discount_type" id="discount_type" class="form-select" required>
                                    <?php foreach ($discount_type_values as $value): ?>
                                        <option value="<?php echo $value; ?>" <?php echo $value == $promotion['discount_type'] ? 'selected' : ''; ?>>
                                            <?php echo $value; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="discount_value" class="form-label">ส่วนลด</label>
                                <div class="input-group">
                                    <input type="number" name="discount_value" id="discount_value"
                                        value="<?php echo $promotion['discount_value']; ?>"
                                        class="form-control" required>
                                    <span class="input-group-text" id="percentLabel" style="display: none;">%</span>
                                </div>
                            </div>
                            <!-- ฟิลด์ pay_people ซึ่งจะถูกแสดง/ซ่อนเมื่อ discount_type เป็น 'count_number' -->
                            <div class="mb-3" id="pay_people_field" style="display: none;">
                                <label for="pay_people" class="form-label">จำนวนคนที่ต้องจ่าย</label>
                                <input type="number" name="pay_people" id="pay_people" value="<?php echo $promotion['pay_people']?? 0; ?>" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label">สถานะโปรโมชั่น</label>
                                <select name="status" id="status" class="form-select" required>
                                    <?php foreach ($status_values as $value): ?>
                                        <option value="<?php echo $value; ?>" <?php echo $value == $promotion['status'] ? 'selected' : ''; ?>>
                                            <?php echo $value; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div">
                                <label for="quantity" class="form-label">จำนวนที่ต้องการ</label>
                                <input type="number" name="quantity" id="quantity" value="<?php echo $promotion['quantity']; ?>" class="form-control" required>
                        </div>

                        <div>
                            <label class="mb-3" for="menu_ids">เมนูที่เกี่ยวข้อง</label>
                            <div>
                                <?php
                                // ดึงเมนูที่เกี่ยวข้องทั้งหมดจากฐานข้อมูล
                                $menus = getMenuItemsGroupedByCategory($conn);
                                $groupedMenus = [];

                                // จัดกลุ่มเมนูตาม category_name
                                foreach ($menus as $menu) {
                                    $groupedMenus[$menu['category_name']][] = $menu;
                                }

                                // ดึง raw_material_id ที่เกี่ยวข้องกับโปรโมชั่นจากฐานข้อมูล
                                $stmt = $conn->prepare("
                                    SELECT m.raw_material_id 
                                    FROM promotion_item pi
                                    JOIN menu m ON pi.menu_id = m.menu_id
                                    WHERE pi.promotion_id = :promotion_id
                                    ");
                                $stmt->execute(['promotion_id' => $promotion_id]);
                                $selectedRawMaterials = $stmt->fetchAll(PDO::FETCH_COLUMN); // ได้เป็น array ของ raw_material_id

                                // แสดงผลเป็น checkbox
                                foreach ($groupedMenus as $category => $items) {
                                    echo "<h4>" . htmlspecialchars($category) . "</h4>";
                                    foreach ($items as $item) {
                                        // ตรวจสอบว่า raw_material_id นี้มีใน selectedRawMaterials หรือไม่
                                        $checked = in_array($item['raw_material_id'], $selectedRawMaterials) ? 'checked' : '';
                                        echo "<label><input type=\"checkbox\" name=\"menu_ids[]\" value=\"{$item['raw_material_id']}\" $checked /> " . htmlspecialchars($item['item_name']) . "</label><br>";
                                    }
                                }
                                ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <a href="Promotion.php" class="btn btn-secondary">กลับ</a>
                            <button type="submit" name="updateRole" class="btn btn-primary">บันทึก</button>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>
</body>