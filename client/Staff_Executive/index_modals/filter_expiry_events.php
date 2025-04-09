<?php
ob_start();

include '../../config/connect_db.php';

$menu_ids = isset($_POST['menu_ids']) ? $_POST['menu_ids'] : [];

if (empty($menu_ids)) {
    echo json_encode([]);
    exit;
}

$ids = implode(',', array_map('intval', $menu_ids)); // กัน SQL injection

$sql = "
    SELECT rm.item_name AS ingredient_name, c.expried_date AS expiry_date
    FROM calculate_raw_material c
    JOIN import_raw_material i ON c.import_raw_material_id = i.import_raw_material_id
    JOIN menu m ON i.menu_id = m.menu_id
    JOIN raw_material rm ON m.raw_material_id = rm.raw_material_id
    WHERE c.expried_date IS NOT NULL
    AND m.menu_id IN ($ids)
";

$result = mysqli_query($conn, $sql);

$events = [];
while ($row = mysqli_fetch_assoc($result)) {
    $events[] = [
        'title' => $row['ingredient_name'],
        'start' => $row['expiry_date']
    ];
}

echo json_encode($events);
?>

<!-- Modal Filter -->
<div class="modal fade" id="expiryFilterCalendarModal" tabindex="-1" aria-labelledby="expiryFilterCalendarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="expiryFilterForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="expiryFilterCalendarModalLabel">กรองข้อมูลวัตถุดิบ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
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


