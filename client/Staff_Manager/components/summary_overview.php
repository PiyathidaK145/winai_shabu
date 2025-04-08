<?php include 'index_modals/filter_summary.php'; ?>

<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">สรุปภาพรวมการใช้บริการ</h5>
        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#filterSummaryModal">filter</button>
    </div>
    <div class="card-body">
        <div class="row text-center align-items-stretch">

            <div class="col-md-2 col-6 mb-3 d-flex">
                <div class="border rounded p-3 shadow-sm bg-light w-100">
                    <h6>จำนวนลูกค้าทั้งหมด</h6>
                    <h4 id="total_customers">-</h4>
                </div>
            </div>

            <div class="col-md-2 col-6 mb-3 d-flex">
                <div class="border rounded p-3 shadow-sm bg-light w-100">
                    <h6>ลูกค้า Walk-in</h6>
                    <h4 id="walkin_customers">-</h4>
                </div>
            </div>

            <div class="col-md-2 col-6 mb-3 d-flex">
                <div class="border rounded p-3 shadow-sm bg-light w-100">
                    <h6>ลูกค้าจองผ่านระบบ</h6>
                    <h4 id="reservation_customers">-</h4>
                </div>
            </div>

            <div class="col-md-3 col-6 mb-3 d-flex">
                <div class="border rounded p-3 shadow-sm bg-light w-100">
                    <h6>เวลาใช้บริการเฉลี่ย</h6>
                    <h4 id="avg_time">-</h4>
                </div>
            </div>

            <div class="col-md-3 col-12 mb-3 d-flex">
                <div class="border rounded p-3 shadow-sm bg-light w-100">
                    <h6>ยอดขาย</h6>
                    <h4 id="total_income">-</h4>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="js/summary_overview.js"></script>
