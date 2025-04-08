<?php include 'index_modals/filter_service_analysis.php'; ?>

<div class="card mb-4">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">การวิเคราะห์การใช้บริการของลูกค้า</h5>
    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#filterServiceAnalysisModal">filter</button>
  </div>

  <div class="card-body text-center">
    <!-- จัดปุ่มให้อยู่ตรงกลาง -->
    <div class="btn-group mb-4" role="group">
      <button class="btn btn-outline-primary active" id="btn_sales">ยอดขาย</button>
      <button class="btn btn-outline-success" id="btn_customers">จำนวนลูกค้า</button>
      <button class="btn btn-outline-warning" id="btn_satisfaction">คะแนนความพึงพอใจ</button>
    </div>

    <!-- จัด Chart ให้อยู่ตรงกลาง และ Responsive -->
    <div class="d-flex justify-content-center">
      <canvas id="analysisChart" style="width: 100%; max-width: 1000px; height: 200px;"></canvas>
    </div>
  </div>
</div>

<script src="js/service_analysis.js"></script>