<?php
date_default_timezone_set("Asia/Bangkok");

include '../../config/connect_db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_name = $_POST['item_name'];
    $description = $_POST['description'];
    $cost = $_POST['cost'];
    $quanity= $_POST['quanity'];
    $unit = $_POST['unit'];
    $Num_before_consumption = $_POST['Num_before_consumption'];

    $warehouse_id = $_POST['warehouse_id'];
    $supplier_id = $_POST['supplier_id'];
    $category_id = $_POST['category_id'];

    $image_url = '';


    $quantity_of_sale = $_POST['quantity_of_sale'];
    $unit_sale = $_POST['unit'];

    $cat_query = mysqli_query($conn, "SELECT category_name FROM category WHERE category_id = '$category_id'");
    $cat_row = mysqli_fetch_assoc($cat_query);
    $category_name = $cat_row['category_name'];

    $upload_dir = "../../img_menu/" . $category_name;

    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // 📸 อัปโหลดไฟล์
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

    $insert_raw = "INSERT INTO raw_material 
        (item_name, description, cost, quanity, unit, Num_before_consumption, warehouse_id, supplier_id, category_id, image_url)
        VALUES 
        ('$item_name', '$description', '$cost', '$quanity', '$unit', '$Num_before_consumption', '$warehouse_id', '$supplier_id', '$category_id', '$image_url')";

    if (mysqli_query($conn, $insert_raw)) {
        $raw_material_id = mysqli_insert_id($conn); // เอา id ล่าสุดที่เพิ่มไป

        // บันทึกเข้าเมนู (เชื่อมวัตถุดิบกับเมนู)
        $insert_menu = "
                INSERT INTO menu (raw_material_id, quantity_of_sale, unit)
                VALUES ('$raw_material_id', '$quantity_of_sale', '$unit')
    ";

        mysqli_query($conn, $insert_menu);

        header("Location: raw_material.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

include dirname(__FILE__) . '/include/header.php';
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>เพิ่มวัตถุดิบ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="row">
        <main class="main-wrapper col-md-9 ms-sm-auto py-4 col-lg-9 px-md-4 border-start">
            <div class="container mt-4">
                <div class="card shadow">
                    <div class="card-header bg-secondary text-white">
                        <h4 class="mb-0">เพิ่มวัตถุดิบ</h4>
                    </div>
                    <div class="card-body">
                        <!-- Form เพิ่ม raw_material -->
                        <form method="POST" enctype="multipart/form-data" action="">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">ชื่อวัตถุดิบ</label>
                                    <input type="text" name="item_name" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">หมวดหมู่</label>
                                    <select name="category_id" id="categorySelect" class="form-select" required>
                                        <option value="">เลือกหมวดหมู่</option>
                                        <?php
                                        $cats = mysqli_query($conn, "SELECT * FROM category");
                                        while ($c = mysqli_fetch_assoc($cats)) {
                                            echo "<option value='{$c['category_id']}'>{$c['category_name']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">คำอธิบาย</label>
                                    <textarea name="description" class="form-control"></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">ซัพพลายเออร์</label>
                                    <select name="supplier_id" id="supplierSelect" class="form-select" required>
                                        <option value="">เลือกซัพพลายเออร์</option>
                                        <!-- จะถูกเติมอัตโนมัติ -->
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">ปริมาณการซื้อ</label>
                                    <input type="number" name="quanity" class="form-control" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">หน่วย</label>
                                    <select name="unit" class="form-select" required>
                                        <option value="g.">g.</option>
                                        <option value="ml.">ml.</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">ราคาต่อปริมาณต่อหน่วย</label>
                                    <input type="number" step="0.01" name="cost" class="form-control" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">จำนวนวันก่อนวันหมดอายุ</label>
                                    <input type="number" name="Num_before_consumption" class="form-control" required>
                                </div>


                                <div class="col-md-3">
                                    <label class="form-label">ปริมาณต่อจาน</label>
                                    <input type="number" name="quantity_of_sale" class="form-control" step="any" required>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">หน่วย</label>
                                    <select name="unit" class="form-select" required>
                                        <option value="g.">g.</option>
                                        <option value="ml.">ml.</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="warehouse_id" class="form-label">คลังวัตถุดิบ</label>
                                    <select name="warehouse_id" id="warehouse_id" class="form-select" required>
                                        <option value="">เลือกคลังวัตถุดิบ</option>
                                        <?php
                                        $warehouse_result = mysqli_query($conn, "SELECT * FROM warehouse");
                                        while ($wh = mysqli_fetch_assoc($warehouse_result)) {
                                            echo "<option value='{$wh['warehouse_id']}'>{$wh['name']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">รูปภาพ</label>
                                    <input type="file" name="image" class="form-control" accept="image/*" required>
                                </div>
                            </div>
                            <div class="text-end mt-4">
                                <a href="raw_material.php" class="btn btn-danger">ยกเลิก</a>
                                <button type="submit" class="btn btn-success">เพิ่มวัตถุดิบ</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <?php include dirname(__FILE__) . '/include/footer.php'; ?>

            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    const categorySelect = document.getElementById("categorySelect");
                    const supplierSelect = document.getElementById("supplierSelect");

                    categorySelect.addEventListener("change", function() {
                        const categoryId = this.value;

                        supplierSelect.innerHTML = '<option>กำลังโหลด...</option>';

                        fetch(`get_suppliers_by_category.php?category_id=${categoryId}`)
                            .then(response => response.json())
                            .then(data => {
                                supplierSelect.innerHTML = '<option value="">เลือกซัพพลายเออร์</option>';
                                data.forEach(supplier => {
                                    const option = document.createElement("option");
                                    option.value = supplier.supplier_id;
                                    option.text = supplier.name;
                                    supplierSelect.appendChild(option);
                                });
                            })
                            .catch(error => {
                                supplierSelect.innerHTML = '<option value="">เกิดข้อผิดพลาด</option>';
                                console.error("Error loading suppliers:", error);
                            });
                    });
                });
            </script>

</body>

</html>