<?php include 'index_modals/filter_customer_service.php'; ?>

<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">ลูกค้าที่เข้าใช้บริการ</h5>
        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#filterCustomerServiceModal">filter</button>
    </div>
    <div class="card-body d-flex justify-content-center align-items-center">
        <canvas id="customerServicePie" style="max-height: 300px; max-width: 300px;"></canvas>
    </div>
</div>

<script src="js/customer_service_chart.js"></script>
