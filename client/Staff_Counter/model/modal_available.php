<!-- สำหรับปุ่มสีเขียว (โต๊ะว่าง) -->
<div class="modal fade" id="walkinModal" tabindex="-1" aria-labelledby="walkinModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content border-dark">
      <div class="modal-header bg-light">
        <h5 class="modal-title" id="walkinModalLabel">Walk-in สำหรับโต๊ะ <span id="walkinTableNumber"></span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="walkinForm" method="post" action="save_walkin.php">
        <div class="modal-body">
          <input type="hidden" name="table_id" id="walkinTableId">
          <div class="mb-3">
            <label class="form-label">ชื่อ</label>
            <input type="text" name="first_name" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">นามสกุล</label>
            <input type="text" name="last_name" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">จำนวนคน</label>
            <input type="number" name="guests" class="form-control" min="1" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">ยืนยัน Walk-in</button>
        </div>
      </form>
    </div>
  </div>
</div>