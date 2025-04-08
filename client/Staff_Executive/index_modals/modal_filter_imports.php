<!-- modal_filter_imports.php -->
<div class="modal fade" id="importFilterModal" tabindex="-1" aria-labelledby="importFilterModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="importFilterForm" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="importFilterModalLabel">กรองรายการนำเข้า</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="ปิด"></button>
      </div>
      <div class="modal-body">

        <div class="mb-3">
          <label>วันที่เริ่มต้น</label>
          <input type="date" name="start_date" class="form-control">
        </div>

        <div class="mb-3">
          <label>วันที่สิ้นสุด</label>
          <input type="date" name="end_date" class="form-control">
        </div>

        <div class="mb-3">
          <label>คลังจัดเก็บ</label>
          <select name="storage_id" class="form-select">
            <option value="">ทั้งหมด</option>
            <?php
            $storages = mysqli_query($conn, "SELECT warehouse_id AS storage_id, name FROM warehouse");
            while ($s = mysqli_fetch_assoc($storages)) {
                echo "<option value='{$s['storage_id']}'>{$s['name']}</option>";
            }
            ?>
          </select>
        </div>

        <div class="mb-3">
          <label>หมวดหมู่</label>
          <select name="category_id" class="form-select">
            <option value="">ทั้งหมด</option>
            <?php
            $categories = mysqli_query($conn, "SELECT * FROM category");
            while ($c = mysqli_fetch_assoc($categories)) {
                echo "<option value='{$c['category_id']}'>{$c['category_name']}</option>";
            }
            ?>
          </select>
        </div>

      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">กรองข้อมูล</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
      </div>
    </form>
  </div>
</div>



