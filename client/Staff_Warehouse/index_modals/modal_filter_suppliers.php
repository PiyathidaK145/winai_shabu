<!-- modal_filter_suppliers.php -->
<div class="modal fade" id="supplierFilterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="supplierFilterForm" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="filterModalLabel">กรองซัพพลายเออร์</h5>
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
          <label>จัดอันดับตาม</label>
          <select name="sort_by" class="form-select">
            <option value="total_price" selected>มูลค่ารวม</option>
            <option value="total_quantity">ปริมาณรวม</option>
            <option value="import_count">จำนวนครั้ง</option>
          </select>
        </div>

      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">ตกลง</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
      </div>
    </form>
  </div>
</div>
