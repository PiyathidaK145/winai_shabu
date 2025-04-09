<?php
date_default_timezone_set("Asia/Bangkok");
include '../../config/connect_db.php';

$raw_material_id = $_GET['id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_name = $_POST['item_name'];
    $description = $_POST['description'];
    $cost = $_POST['cost'];
    $quanity = $_POST['quanity'];
    $unit = $_POST['unit'];
    $Num_before_consumption = $_POST['Num_before_consumption'];
    $warehouse_id = $_POST['warehouse_id'];
    $supplier_id = $_POST['supplier_id'];
    $category_id = $_POST['category_id'];
    $quantity_of_sale = $_POST['quantity_of_sale'];

    // ดึงชื่อ category สำหรับ path รูปภาพ
    $cat_query = mysqli_query($conn, "SELECT category_name FROM category WHERE category_id = '$category_id'");
    $cat_row = mysqli_fetch_assoc($cat_query);
    $category_name = $cat_row['category_name'];
    $upload_dir = "../../img_menu/" . $category_name;

    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // ดึงข้อมูลเดิมก่อนอัปเดต
    $old_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT image_url FROM raw_material WHERE raw_material_id = '$raw_material_id'"));
    $image_url = $old_data['image_url'];

    // ถ้ามีการอัปโหลดไฟล์ใหม่
    if (!empty($_FILES['image']['name'])) {
        $filename = time() . "_" . basename($_FILES['image']['name']);
        $target_path = $upload_dir . "/" . $filename;
        $relative_path = "img_menu/" . $category_name . "/" . $filename;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
            $image_url = $relative_path;
        } else {
            echo "<script>alert('อัปโหลดรูปภาพไม่สำเร็จ');</script>";
        }
    }

    // อัปเดต raw_material
    $update_raw = "
        UPDATE raw_material SET
        item_name = '$item_name',
        description = '$description',
        cost = '$cost',
        quanity = '$quanity',
        unit = '$unit',
        Num_before_consumption = '$Num_before_consumption',
        warehouse_id = '$warehouse_id',
        supplier_id = '$supplier_id',
        category_id = '$category_id',
        image_url = '$image_url'
        WHERE raw_material_id = '$raw_material_id'
    ";
    mysqli_query($conn, $update_raw);

    // อัปเดต menu
    $update_menu = "
        UPDATE menu SET
        quantity_of_sale = '$quantity_of_sale',
        unit = '$unit'
        WHERE raw_material_id = '$raw_material_id'
    ";
    mysqli_query($conn, $update_menu);

    header("Location: raw_material.php");
    exit();
}

// ดึงข้อมูลเดิม
$raw = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM raw_material WHERE raw_material_id = '$raw_material_id'"));
$menu = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM menu WHERE raw_material_id = '$raw_material_id'"));

include dirname(__FILE__) . '/include/header.php';
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>แก้ไขวัตถุดิบ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<div class="row">
    <main class="main-wrapper col-md-9 ms-sm-auto py-4 col-lg-9 px-md-4 border-start">
        <div class="container mt-4">
            <div class="card shadow">
                <div class="card-header bg-secondary text-white">
                    <h4 class="mb-0">แก้ไขวัตถุดิบ</h4>
                </div>
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">ชื่อวัตถุดิบ</label>
                                <input type="text" name="item_name" class="form-control" value="<?= $raw['item_name'] ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">หมวดหมู่</label>
                                <select name="category_id" id="categorySelect" class="form-select" required>
                                    <?php
                                    $cats = mysqli_query($conn, "SELECT * FROM category");
                                    while ($c = mysqli_fetch_assoc($cats)) {
                                        $selected = ($raw['category_id'] == $c['category_id']) ? 'selected' : '';
                                        echo "<option value='{$c['category_id']}' $selected>{$c['category_name']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">คำอธิบาย</label>
                                <textarea name="description" class="form-control"><?= $raw['description'] ?></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">ซัพพลายเออร์</label>
                                <select name="supplier_id" class="form-select" required>
                                    <?php
                                    $suppliers = mysqli_query($conn, "SELECT * FROM supplier WHERE category_id = '{$raw['category_id']}'");
                                    while ($s = mysqli_fetch_assoc($suppliers)) {
                                        $selected = ($raw['supplier_id'] == $s['supplier_id']) ? 'selected' : '';
                                        echo "<option value='{$s['supplier_id']}' $selected>{$s['name']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">ปริมาณการซื้อ</label>
                                <input type="number" name="quanity" class="form-control" value="<?= $raw['quanity'] ?>" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">หน่วย</label>
                                <select name="unit" class="form-select" required>
                                    <option value="g." <?= $raw['unit'] == 'g.' ? 'selected' : '' ?>>g.</option>
                                    <option value="ml." <?= $raw['unit'] == 'ml.' ? 'selected' : '' ?>>ml.</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">ราคาต่อปริมาณต่อหน่วย</label>
                                <input type="number" name="cost" class="form-control" value="<?= $raw['cost'] ?>" step="0.01" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">วันก่อนหมดอายุ</label>
                                <input type="number" name="Num_before_consumption" class="form-control" value="<?= $raw['Num_before_consumption'] ?>" required>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">ปริมาณต่อจาน</label>
                                <input type="number" name="quantity_of_sale" class="form-control" value="<?= $menu['quantity_of_sale'] ?>" step="any" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">คลังวัตถุดิบ</label>
                                <select name="warehouse_id" class="form-select" required>
                                    <?php
                                    $warehouses = mysqli_query($conn, "SELECT * FROM warehouse");
                                    while ($w = mysqli_fetch_assoc($warehouses)) {
                                        $selected = ($raw['warehouse_id'] == $w['warehouse_id']) ? 'selected' : '';
                                        echo "<option value='{$w['warehouse_id']}' $selected>{$w['name']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">รูปภาพ</label>
                                <input type="file" name="image" class="form-control" accept="image/*">
                                <?php if (!empty($raw['image_url'])): ?>
                                    <small>รูปปัจจุบัน: <img src="../../<?= $raw['image_url'] ?>" width="80"></small>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="text-end mt-4">
                            <a href="raw_material.php" class="btn btn-danger">ย้อนกลับ</a>
                            <button type="submit" class="btn btn-warning">บันทึกการเปลี่ยนแปลง</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>

<?php include dirname(__FILE__) . '/include/footer.php'; ?>
</body>
</html>
