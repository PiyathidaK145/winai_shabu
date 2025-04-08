<div class="modal fade" id="filterPackageChartModal" tabindex="-1" aria-labelledby="filterLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <form id="filterPackageChartForm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">กรองข้อมูลแพ็คเกจ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="ปิด"></button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row g-3">
                            <!-- วันที่เริ่มต้น -->
                            <div class="col-md-6">
                                <label class="form-label">วันที่เริ่มต้น</label>
                                <input type="date" name="start_date" class="form-control">
                            </div>

                            <!-- วันที่สิ้นสุด -->
                            <div class="col-md-6">
                                <label class="form-label">วันที่สิ้นสุด</label>
                                <input type="date" name="end_date" class="form-control">
                            </div>

                            <!-- ประเภทการใช้บริการ -->
                            <div class="col-md-6">
                                <label class="form-label">ประเภทการใช้บริการ</label>
                                <select name="service_type" class="form-select">
                                    <option value="">ทั้งหมด</option>
                                    <option value="walkin">Walk-in</option>
                                    <option value="reservation">Reservation</option>
                                </select>
                            </div>

                            <!-- โปรโมชั่น -->
                            <div class="col-md-6">
                                <label class="form-label">โปรโมชั่น</label>
                                <select name="promotion" id="promotion_package_chart" class="form-select">
                                    <option value="">ทั้งหมด</option>
                                </select>
                            </div>

                            <!-- เพศ -->
                            <div class="col-md-6">
                                <label class="form-label">เพศ</label>
                                <select name="gender" id="gender_package_chart" class="form-select">
                                    <option value="">ทั้งหมด</option>
                                </select>
                            </div>

                            <!-- ศาสนา -->
                            <div class="col-md-6">
                                <label class="form-label">ศาสนา</label>
                                <select name="religion" id="religion_package_chart" class="form-select">
                                    <option value="">ทั้งหมด</option>
                                </select>
                            </div>

                            <!-- หมายเลขโต๊ะ -->
                            <div class="col-md-6">
                                <label class="form-label">หมายเลขโต๊ะ</label>
                                <select name="table_id" id="table_package_chart" class="form-select">
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
