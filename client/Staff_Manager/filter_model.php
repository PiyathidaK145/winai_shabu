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
$ratings = [1, 2, 3, 4, 5];
?>

<div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="GET" action="comment_list.php">
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
                        foreach ($options as $opt) {
                            $check = (isset($selected) && is_array($selected) && in_array($opt, $selected)) ? 'checked' : '';
                            echo "<li><div class='form-check'>
                        <input class='form-check-input' type='checkbox' name='{$name}[]' value='$opt' $check>
                        <label class='form-check-label'>$opt</label>
                      </div></li>";
                        }
                        echo "</ul></div></div>";
                    }

                    renderDropdown('เพศ', 'gender', $genders, $_GET['gender'] ?? []);
                    renderDropdown('ศาสนา', 'religion', $religions, $_GET['religion'] ?? []);
                    renderDropdown('โต๊ะ', 'table_id', $tables, $_GET['table_id'] ?? []);
                    renderDropdown('แพ็คเกจ', 'package_name', $packages, $_GET['package_name'] ?? []);
                    renderDropdown('โปรโมชัน', 'promotions_name', $promotions, $_GET['promotions_name'] ?? []);
                    renderDropdown('ระดับความพึงพอใจ', 'rating_avg', $ratings, $_GET['rating_avg'] ?? []);
                    ?>

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
    }
</script>