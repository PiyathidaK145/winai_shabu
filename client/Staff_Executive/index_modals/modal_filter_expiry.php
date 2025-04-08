<?php include dirname(__FILE__) . '/../../../config/connect_db.php'; ?>

<div class="modal fade" id="expiryFilterModal" tabindex="-1" aria-labelledby="expiryFilterLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="expiryFilterForm" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="expiryFilterLabel">กรองวัตถุดิบใกล้หมดอายุ</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="ปิด"></button>
      </div>

      <div class="modal-body">

        <!-- วันที่เริ่มต้น -->
        <div class="mb-3">
          <label for="start_date" class="form-label">วันที่เริ่มต้น</label>
          <input type="date" name="start_date" id="start_date" class="form-control">
        </div>

        <!-- วันที่สิ้นสุด -->
        <div class="mb-3">
          <label for="end_date" class="form-label">วันที่สิ้นสุด</label>
          <input type="date" name="end_date" id="end_date" class="form-control">
        </div>

        <!-- คลังจัดเก็บ -->
        <div class="mb-3">
          <label for="storage_id" class="form-label">คลังจัดเก็บ</label>
          <select name="storage_id" id="storage_id" class="form-select">
            <option value="">ทั้งหมด</option>
            <?php
            $warehouses = mysqli_query($conn, "SELECT warehouse_id, name FROM warehouse");
            while ($w = mysqli_fetch_assoc($warehouses)) {
                echo "<option value='{$w['warehouse_id']}'>{$w['name']}</option>";
            }
            ?>
          </select>
        </div>

        <!-- หมวดหมู่ -->
        <div class="mb-3">
          <label for="category_id" class="form-label">หมวดหมู่</label>
          <select name="category_id" id="category_id" class="form-select">
            <option value="">ทั้งหมด</option>
            <?php
            $categories = mysqli_query($conn, "SELECT category_id, category_name FROM category");
            while ($c = mysqli_fetch_assoc($categories)) {
                echo "<option value='{$c['category_id']}'>{$c['category_name']}</option>";
            }
            ?>
          </select>
        </div>

      </div>

      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">กรอง</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
      </div>
    </form>
  </div>
</div>
