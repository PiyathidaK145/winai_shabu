<?php include 'index_modals/filter_package_chart.php'; ?>

<div class="card mb-4">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">ลูกค้าที่ใช้แพ็คเกจต่างๆ</h5>
    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#filterPackageChartModal">filter</button>
  </div>
  <div class="card-body">
    <canvas id="packagePieChart" style="max-height: 300px;"></canvas>
  </div>
</div>

<script src="js/package_chart.js"></script>
