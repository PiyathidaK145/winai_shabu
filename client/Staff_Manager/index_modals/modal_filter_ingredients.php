<?php
include '../../config/connect_db.php';

// ดึงข้อมูลวัตถุดิบและหมวดหมู่
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

// จัดกลุ่มตาม category
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
?>

<!-- Modal Filter -->
<div class="modal fade" id="ingredientFilterModal" tabindex="-1" aria-labelledby="ingredientFilterModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="ingredientFilterForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="ingredientFilterModalLabel">กรองข้อมูลวัตถุดิบ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="start_date">วันที่เริ่มต้น</label>
                            <input type="date" class="form-control" name="start_date" id="start_date">
                        </div>
                        <div class="col-md-6">
                            <label for="end_date">วันที่สิ้นสุด</label>
                            <input type="date" class="form-control" name="end_date" id="end_date">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>เลือกวัตถุดิบ</label>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="all_ingredients" checked>
                            <label class="form-check-label" for="all_ingredients">เลือกทั้งหมด</label>
                        </div>

                        <?php foreach ($menus_by_category as $cat_id => $cat): ?>
                            <div class="mb-2 ps-2">
                                <strong><?= htmlspecialchars($cat['category_name']) ?></strong><br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input select-all" type="checkbox" id="select_all_<?= $cat_id ?>" data-category="<?= $cat_id ?>">
                                    <label class="form-check-label text-danger" for="select_all_<?= $cat_id ?>">เลือกทั้งหมดในหมวดนี้</label>
                                </div><br>
                                <?php foreach ($cat['menus'] as $menu): ?>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input menu-checkbox" type="checkbox" name="menu_ids[]" value="<?= $menu['menu_id'] ?>" data-category="<?= $cat_id ?>">
                                        <label class="form-check-label"><?= htmlspecialchars($menu['item_name']) ?></label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <hr>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">ตกลง</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JS: Handle Select All -->
<script>
    document.getElementById('all_ingredients').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.menu-checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });

    // สำหรับเลือกทั้งหมดในแต่ละหมวด
    document.querySelectorAll('.select-all').forEach(selectAllCheckbox => {
        selectAllCheckbox.addEventListener('change', function() {
            const category = this.getAttribute('data-category');
            const checkboxes = document.querySelectorAll(`.menu-checkbox[data-category='${category}']`);
            checkboxes.forEach(cb => cb.checked = this.checked);
        });
    });
    
    document.addEventListener('DOMContentLoaded', () => {
    const buttons = document.querySelectorAll('[data-bs-toggle="modal"]');

    buttons.forEach(btn => {
        btn.addEventListener('click', () => {
            console.log('🟢 Button clicked:', btn);

            const target = btn.getAttribute('data-bs-target');
            const modal = document.querySelector(target);

            if (modal) {
                console.log('✅ Found modal:', modal);
                console.log('✅ Modal has correct classes:', modal.classList.contains('modal') && modal.classList.contains('fade'));

                const instance = bootstrap.Modal.getOrCreateInstance(modal);
                instance.show(); // 👈 ลองเรียกด้วยตัวเอง
            } else {
                console.warn('❌ Modal not found:', target);
            }
        });
    });

        // ตรวจจับการเปิดโมเดลจริง ๆ
        const allModals = document.querySelectorAll('.modal');
        allModals.forEach(modal => {
            modal.addEventListener('shown.bs.modal', function() {
                console.log(`📢 Modal "${modal.id}" has been shown.`);
            });

            modal.addEventListener('hidden.bs.modal', function() {
                console.log(`📪 Modal "${modal.id}" has been closed.`);
            });
        });
    });
</script>