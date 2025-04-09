<?php
include dirname(__FILE__) . '/../../config/connect_db.php';

function getOptions($conn, $table, $column)
{
    $sql = "SELECT DISTINCT $column FROM $table WHERE $column IS NOT NULL AND $column != '' ORDER BY $column ASC";
    $result = mysqli_query($conn, $sql);
    $options = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $options[] = $row[$column];
    }
    return $options;
}

$genders = getOptions($conn, 'customer', 'gender');
$religions = getOptions($conn, 'customer', 'religion');
$tables = getOptions($conn, '`table`', 'table_id');
$packages = getOptions($conn, 'package', 'package_name');
$promotions = getOptions($conn, 'promotion', 'promotions_name');
$payment_methods = getOptions($conn, 'payment', 'payment_method');
$ratings = [1, 2, 3, 4, 5];
$service_types = ['walkin' => 'Walk-in', 'reservation' => 'Reservation'];
$durations = [
    '0-30' => '0 - 30 นาที',
    '30-60' => '30 นาที - 1 ชั่วโมง',
    '60-90' => '1 ชั่วโมง - 1.30 ชั่วโมง',
    '90-120' => '1.30 ชั่วโมง - 2 ชั่วโมง'
];
?>

<div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="GET" action="history_customer.php">
                <div class="modal-header">
                    <h5 class="modal-title" id="filterModalLabel">ตัวกรองความคิดเห็น</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body row">

                    <?php
                    function renderDropdown($label, $name, $options, $selected = [])
                    {
                        echo "<div class='col-md-4 mb-3'>";
                        echo "<label class='form-label'>$label</label>";
                        echo "<div class='dropdown'>";
                        echo "<button class='btn btn-outline-secondary w-100 dropdown-toggle' type='button' id='{$name}_dropdown' data-bs-toggle='dropdown' aria-expanded='false'>เลือก $label</button>";
                        echo "<ul class='dropdown-menu w-100 px-3' aria-labelledby='{$name}_dropdown' style='max-height:200px; overflow-y:auto;'>";
                        foreach ($options as $value => $labelOpt) {
                            $val = is_numeric($value) ? $labelOpt : $value;
                            $labelText = is_numeric($value) ? $labelOpt : $labelOpt;
                            $check = (isset($selected) && is_array($selected) && in_array($val, $selected)) ? 'checked' : '';
                            echo "<li><div class='form-check'>
                                <input class='form-check-input' type='checkbox' name='{$name}[]' value='$val' $check>
                                <label class='form-check-label'>$labelText</label>
                              </div></li>";
                        }
                        echo "</ul></div></div>";
                    }

                    renderDropdown('เพศ', 'gender', array_combine($genders, $genders), $_GET['gender'] ?? []);
                    renderDropdown('ศาสนา', 'religion', array_combine($religions, $religions), $_GET['religion'] ?? []);
                    renderDropdown('โต๊ะ', 'table_id', array_combine($tables, $tables), $_GET['table_id'] ?? []);
                    renderDropdown('แพ็คเกจ', 'package_name', array_combine($packages, $packages), $_GET['package_name'] ?? []);
                    renderDropdown('โปรโมชัน', 'promotions_name', array_combine($promotions, $promotions), $_GET['promotions_name'] ?? []);
                    renderDropdown('ระดับความพึงพอใจ', 'rating_avg', array_combine($ratings, $ratings), $_GET['rating_avg'] ?? []);
                    renderDropdown('ประเภทการใช้บริการ', 'service_type', $service_types, $_GET['service_type'] ?? []);

                    renderDropdown('วิธีการชำระเงิน', 'payment_method', array_combine($payment_methods, $payment_methods), $_GET['payment_method'] ?? []);
                    ?>

                    <!-- ช่วงระยะเวลาใช้บริการ -->
                    <div class="col-md-4 mb-3">
                        <label class="form-label">ช่วงระยะเวลาใช้บริการ</label>
                        <select name="duration_range" class="form-select">
                            <option value="">-- ทั้งหมด --</option>
                            <option value="0-30" <?= ($_GET['duration_range'] ?? '') === '0-30' ? 'selected' : '' ?>>0 - 30 นาที</option>
                            <option value="30-60" <?= ($_GET['duration_range'] ?? '') === '30-60' ? 'selected' : '' ?>>30 นาที - 1 ชั่วโมง</option>
                            <option value="60-90" <?= ($_GET['duration_range'] ?? '') === '60-90' ? 'selected' : '' ?>>1 ชั่วโมง - 1.30 ชั่วโมง</option>
                            <option value="90-120" <?= ($_GET['duration_range'] ?? '') === '90-120' ? 'selected' : '' ?>>1.30 ชั่วโมง - 2 ชั่วโมง</option>
                        </select>
                    </div>

                    <!-- วันที่ -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">ช่วงวันที่</label>
                        <div class="input-group">
                            <input type="date" name="start_date" class="form-control" value="<?= $_GET['start_date'] ?? '' ?>">
                            <span class="input-group-text">ถึง</span>
                            <input type="date" name="end_date" class="form-control" value="<?= $_GET['end_date'] ?? '' ?>">
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="resetFilter()">ล้างค่า</button>
                    <button type="submit" class="btn btn-primary">กรองข้อมูล</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function resetFilter() {
        const form = document.querySelector('#filterModal form');

        // Reset all standard form fields
        form.reset();

        // Uncheck all custom checkboxes
        form.querySelectorAll('input[type=checkbox]').forEach(cb => cb.checked = false);

        form.querySelectorAll('select').forEach(select => select.selectedIndex = 0);
    }
</script>