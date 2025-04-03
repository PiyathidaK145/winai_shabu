<!-- Modal สำหรับโต๊ะที่จองแล้ว -->
<div class="modal fade" id="reservedModal" tabindex="-1" aria-labelledby="reservedModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border border-warning">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="reservedModalLabel">รายละเอียดการจอง</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <p><strong class="text-dark">หมายเลขโต๊ะ:</strong> <span id="reservedTableNumber" class="text-dark">-</span></p>
                <input type="hidden" id="confirmTableId" name="table_id">

                <div id="bookingDetails" style="display: none;">
                    <p><strong class="text-dark">ชื่อ:</strong> <span id="reservedFirstName" class="text-dark"></span></p>
                    <p><strong class="text-dark">นามสกุล:</strong> <span id="reservedLastName" class="text-dark"></span></p>
                    <p><strong class="text-dark">จำนวนคน:</strong> <span id="reservedGuests" class="text-dark"></span></p>
                </div>

                <p><strong class="text-dark">ช่วงเวลา:</strong> <span id="reservedTimeSlot" class="text-dark">-</span></p>

                <div class="mb-3">
                    <label for="bookingCodeInput" class="form-label">กรอกรหัสการจอง</label>
                    <input type="text" class="form-control" id="bookingCodeInput" placeholder="เช่น RES12345">
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

<div class="modal fade" id="walkinReservedModal" tabindex="-1" aria-labelledby="walkinReservedModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="walkinReservedModalLabel">รายละเอียดลูกค้า Walk-in</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-dark">
                <input type="hidden" id="confirmTableId_walkin" name="table_id">
                <p><strong>หมายเลขโต๊ะ:</strong> <span id="walkinTableNumber"></span></p>
                <p><strong>ช่วงเวลา:</strong> <span id="walkinTimeSlot"></span></p>
                <p><strong>ชื่อ:</strong> <span id="walkinFirstName"></span></p>
                <p><strong>นามสกุล:</strong> <span id="walkinLastName"></span></p>
                <p><strong>จำนวนคน:</strong> <span id="walkinGuests"></span></p>
            </div>
        </div>
    </div>
</div>