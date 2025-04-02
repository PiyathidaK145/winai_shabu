<!-- Modal สำหรับโต๊ะที่จองแล้ว -->
<div class="modal fade" id="reservedModal" tabindex="-1" aria-labelledby="reservedModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content border border-warning">
      <div class="modal-header bg-warning text-dark">
        <h5 class="modal-title" id="reservedModalLabel">รายละเอียดการจอง</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <p><strong>หมายเลขโต๊ะ:</strong> <span id="reservedTableNumber">-</span></p>
        <p><strong>ช่วงเวลา:</strong> <span id="reservedTimeSlot">-</span></p>

        <div class="mb-3">
          <label for="bookingCodeInput" class="form-label">กรอกรหัสการจอง</label>
          <input type="text" class="form-control" id="bookingCodeInput" placeholder="เช่น RES12345">
        </div>

        <div id="bookingDetails" style="display: none;">
          <p><strong>ชื่อ:</strong> <span id="reservedFirstName"></span></p>
          <p><strong>นามสกุล:</strong> <span id="reservedLastName"></span></p>
          <p><strong>จำนวนคน:</strong> <span id="reservedGuests"></span></p>
        </div>

        <div id="bookingNotFound" class="text-danger" style="display: none;">
          ❌ ไม่พบข้อมูลการจองในระบบ
        </div>
      </div>

      <div class="modal-footer">
        <button class="btn btn-primary" id="checkBookingBtn">ตรวจสอบรหัส</button>
        <button class="btn btn-success d-none" id="confirmReservationBtn">ยืนยันการจอง</button>
      </div>
    </div>
  </div>
</div>
