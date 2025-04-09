<?php
date_default_timezone_set("Asia/Bangkok");

include '../../config/connect_db.php';
// ดึงหมวดหมู่
$category_query = "SELECT * FROM category";
$category_result = mysqli_query($conn, $category_query);

// ดึง raw_material + JOIN menu เพื่อดึง menu_id
$material_query = "
    SELECT 
        m.menu_id,
        r.item_name,
        r.category_id,
        c.category_name
    FROM 
        menu AS m
    INNER JOIN raw_material AS r ON m.raw_material_id = r.raw_material_id
    INNER JOIN category AS c ON r.category_id = c.category_id
    ORDER BY c.category_name ASC, r.item_name ASC
";
$material_result = mysqli_query($conn, $material_query);

// จัดกลุ่มเมนูตาม category
$menus_by_category = [];
while ($row = mysqli_fetch_assoc($material_result)) {
    $cat_id = $row['category_id'];
    if (!isset($menus_by_category[$cat_id])) {
        $menus_by_category[$cat_id] = [
            'category_name' => $row['category_name'],
            'menus' => []
        ];
    }
    $menus_by_category[$cat_id]['menus'][] = $row;
}


include dirname(__FILE__) . '/include/header.php';
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>เพิ่มแพ็คเกจ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="row">
        <main class="main-wrapper col-md-9 ms-sm-auto py-4 col-lg-9 px-md-4 border-start">
            <div class="container mt-4">
                <div class="card shadow">
                    <div class="card-header bg-secondary text-white">
                        <h4 class="mb-0">เพิ่มเพ็คเกจ</h4>
                    </div>
                    <div class="card-body">
                        <form action="save_package.php" method="POST">
                            <div class="mb-3">
                                <label for="package_name" class="form-label">ชื่อแพ็คเกจ</label>
                                <input type="text" name="package_name" id="package_name" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="discription" class="form-label">คำอธิบาย</label>
                                <textarea name="discription" id="discription" rows="3" class="form-control" required></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="price" class="form-label">ราคา (บาท)</label>
                                <input type="number" name="price" id="price" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">เลือกเมนูในแพ็คเกจ (แยกตามหมวดหมู่)</label>
                                <?php foreach ($menus_by_category as $cat_id => $cat_data): ?>
                                    <div class="mb-2">
                                        <strong><?= htmlspecialchars($cat_data['category_name']) ?></strong>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input select-all" type="checkbox" id="select_all_<?= $cat_id ?>" data-category="<?= $cat_id ?>">
                                            <label class="form-check-label text-danger fw-bold" for="select_all_<?= $cat_id ?>">เลือกทั้งหมด</label>
                                        </div><br>
                                        <?php foreach ($cat_data['menus'] as $menu): ?>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input menu-checkbox" type="checkbox" name="menu_ids[]" value="<?= $menu['menu_id'] ?>" data-category="<?= $cat_id ?>" id="menu_<?= $menu['menu_id'] ?>">
                                                <label class="form-check-label" for="menu_<?= $menu['menu_id'] ?>">
                                                    <?= htmlspecialchars($menu['item_name']) ?>
                                                </label>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <hr>
                                <?php endforeach; ?>
                            </div>

                            <div class="text-end">
                                <a href="package.php" class="btn btn-danger">ยกเลิก</a>
                                <button type="submit" class="btn btn-primary">บันทึกแพ็คเกจ</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </main>
    </div>
    <script>
        // เมื่อคลิก checkbox "เลือกทั้งหมด"
        document.querySelectorAll('.select-all').forEach(selectAllCheckbox => {
            selectAllCheckbox.addEventListener('change', function() {
                const category = this.getAttribute('data-category');
                const checkboxes = document.querySelectorAll(`.menu-checkbox[data-category='${category}']`);
                checkboxes.forEach(cb => cb.checked = this.checked);
            });
        });
    </script>

</body>

</html>