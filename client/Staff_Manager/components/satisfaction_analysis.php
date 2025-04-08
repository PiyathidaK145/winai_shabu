<?php include 'index_modals/filter_satisfaction_analysis.php'; ?>

<div class="card mb-4">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">การวิเคราะห์ความพึงพอใจของลูกค้า</h5>
    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#filterSatisfactionModal">filter</button>
  </div>

  <div class="card-body">
    <div class="row">
      <!-- ฝั่งซ้าย: การ์ด 5 แถว 1 คอลัมน์ -->
      <div class="col-md-4">
        <div class="text-center mb-3">
          <div class="border rounded p-3 shadow-sm bg-light">
            <h6>บริการ</h6>
            <h4 id="tag_service">-</h4>
          </div>
        </div>
        <div class="text-center mb-3">
          <div class="border rounded p-3 shadow-sm bg-light">
            <h6>ความสะอาด</h6>
            <h4 id="tag_cleanliness">-</h4>
          </div>
        </div>
        <div class="text-center mb-3">
          <div class="border rounded p-3 shadow-sm bg-light">
            <h6>อาหาร</h6>
            <h4 id="tag_food">-</h4>
          </div>
        </div>
        <div class="text-center mb-3">
          <div class="border rounded p-3 shadow-sm bg-light">
            <h6>ราคา</h6>
            <h4 id="tag_price">-</h4>
          </div>
        </div>
        <div class="text-center mb-3">
          <div class="border rounded p-3 shadow-sm bg-light">
            <h6>อื่นๆ</h6>
            <h4 id="tag_other">-</h4>
          </div>
        </div>
        
      </div>

      <!-- ฝั่งขวา: กราฟ + คะแนนเฉลี่ย -->
      <div class="col-md-8 d-flex align-items-center justify-content-center">
        <div class="border rounded p-4 shadow-sm bg-light w-100 text-center">
          <h6>คะแนนเฉลี่ยความพึงพอใจ</h6>
          <canvas id="satisfactionPieChart" style="max-height: 250px;"></canvas>
          <h1 id="avg_satisfaction">-</h1>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="js/satisfaction_analysis.js"></script>
