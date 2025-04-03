<!-- Modal สำหรับโต๊ะที่จองแล้ว -->
<div class="modal fade" id="reservedModal" tabindex="-1" aria-labelledby="reservedModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border border-warning shadow rounded-3">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title fw-bold" id="reservedModalLabel">รายละเอียดการจอง</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="ปิด"></button>
            </div>

            <div class="modal-body text-dark">
                <input type="hidden" id="confirmTableId" name="table_id">
                <input type="hidden" id="reservationIdHidden" name="reservation_id">

                <!-- 2 Column Layout -->
                <div class="row mb-3">
                    <div class="col-6">
                        <p class="mb-1"><strong class="text-dark">ชื่อ:</strong> <span id="reservedFirstName" class="text-dark"></span></p>
                    </div>
                    <div class="col-6">
                        <p class="mb-1"><strong class="text-dark">นามสกุล:</strong> <span id="reservedLastName" class="text-dark"></span></p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-6">
                        <p class="mb-1"><strong class="text-dark">หมายเลขโต๊ะ:</strong> <span id="reservedTableNumber" class="text-dark">-</span></p>
                    </div>
                    <div class="col-6">
                        <p class="mb-1"><strong class="text-dark">ช่วงเวลา:</strong> <span id="reservedTimeSlot" class="text-dark">-</span></p>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-12">
                        <p class="mb-1"><strong class="text-dark">จำนวนคน:</strong> <span id="reservedGuests" class="text-dark"></span></p>
                    </div>
                </div>

                <!-- Input code -->
                <div>
                    <label for="reservationCode">กรอกรหัสการจอง:</label>
                    <input type="text" class="form-control" id="reservationCode">
                </div>


            </div>

            <div class="modal-footer justify-content-end">
                <a href="#" class="btn btn-primary" id="checkBookingBtn">ตรวจสอบรหัส</a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="walkinReservedModal" tabindex="-1" aria-labelledby="walkinReservedModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border border-warning shadow rounded-3">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title fw-bold" id="walkinReservedModalLabel">รายละเอียด Walk-in</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="ปิด"></button>
            </div>

            <div class="modal-body text-dark">
                <!-- รหัสอยู่ตรงกลาง -->
                <p class="h4 text-center fw-bold text-dark mb-4" id="walkinCodeDisplay">รหัส - XXXX</p>

                <!-- 2 Column Layout -->
                <div class="row mb-3">
                    <div class="col-6">
                        <p class="mb-1"><strong class="text-dark">ชื่อ:</strong> <span id="walkinFirstName" class="text-dark"></span></p>
                    </div>
                    <div class="col-6">
                        <p class="mb-1"><strong class="text-dark">นามสกุล:</strong> <span id="walkinLastName" class="text-dark"></span></p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-6">
                        <p class="mb-1"><strong class="text-dark">หมายเลขโต๊ะ:</strong> <span id="walkinTableNumber" class="text-dark"></span></p>
                    </div>
                    <div class="col-6">
                        <p class="mb-1"><strong class="text-dark">ช่วงเวลา:</strong> <span id="walkinTimeSlot" class="text-dark"></span></p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <p class="mb-1"><strong class="text-dark">จำนวนคน:</strong> <span id="walkinGuests" class="text-dark"></span></p>
                    </div>
                </div>
            </div>

            <div class="modal-footer justify-content-end">
                <a href="#" class="btn btn-primary" id="verifyWalkinBtn">รับโต๊ะ</a>
            </div>
        </div>
    </div>
</div>