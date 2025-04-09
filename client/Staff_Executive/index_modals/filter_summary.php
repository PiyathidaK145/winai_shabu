<div class="modal fade" id="filterSummaryModal" tabindex="-1" aria-labelledby="filterSummaryLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg"> <!-- เพิ่ม modal-lg ให้ใหญ่ขึ้น -->
        <form id="filterSummaryForm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">กรองข้อมูล</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="ปิด"></button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row g-3">
                            <!-- วันที่เริ่มต้น -->
                            <div class="col-md-6">
                                <label for="start_date" class="form-label">วันที่เริ่มต้น</label>
                                <input type="date" class="form-control" name="start_date" id="start_date">
                            </div>
                            <!-- วันที่สิ้นสุด -->
                            <div class="col-md-6">
                                <label for="end_date" class="form-label">วันที่สิ้นสุด</label>
                                <input type="date" class="form-control" name="end_date" id="end_date">
                            </div>

                            <!-- แพ็คเกจ -->
                            <div class="col-md-6">
                                <label for="package" class="form-label">แพ็คเกจ</label>
                                <select name="package" id="package" class="form-select">
                                    <option value="">ทั้งหมด</option>
                                </select>
                            </div>

                            <!-- โปรโมชั่น -->
                            <div class="col-md-6">
                                <label for="promotion" class="form-label">โปรโมชั่น</label>
                                <select name="promotion" id="promotion" class="form-select">
                                    <option value="">ทั้งหมด</option>
                                </select>
                            </div>

                            <!-- เพศ -->
                            <div class="col-md-6">
                                <label for="gender_service_analysis" class="form-label">เพศ</label>
                                <select name="gender" id="gender_service_analysis" class="form-select">
                                    <option value="">ทั้งหมด</option>
                                </select>
                            </div>

                            <!-- ศาสนา -->
                            <div class="col-md-6">
                                <label for="religion_service_analysis" class="form-label">ศาสนา</label>
                                <select name="religion" id="religion_service_analysis" class="form-select">
                                    <option value="">ทั้งหมด</option>
                                </select>
                            </div>

                            <!-- หมายเลขโต๊ะ -->
                            <div class="col-md-6">
                                <label for="table" class="form-label">หมายเลขโต๊ะ</label>
                                <select name="table_id" id="table_summary" class="form-select">
                                    <option value="">ทั้งหมด</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">ใช้ตัวกรอง</button>
                </div>
            </div>
        </form>
    </div>
</div>
