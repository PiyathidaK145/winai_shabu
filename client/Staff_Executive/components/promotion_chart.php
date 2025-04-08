<?php include 'index_modals/filter_promotion_chart.php'; ?>

<div class="card mb-4">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">ลูกค้าที่ใช้โปรโมชั่นต่างๆ</h5>
    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#filterPromotionChartModal">filter</button>
  </div>
  <div class="card-body">
    <canvas id="promotionPieChart" style="max-height: 300px;"></canvas>
  </div>
</div>

<script src="js/promotion_chart.js"></script>
