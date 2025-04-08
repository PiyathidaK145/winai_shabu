<?php include 'index_modals/filter_popular_service.php'; ?>

<div class="card mb-4">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">บริการยอดนิยม</h5>
    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#filterPopularServiceModal">filter</button>
  </div>
  <div class="card-body">
    <div class="row text-center">

      <div class="col-12 mb-3 d-flex justify-content-center">
        <div class="border rounded p-3 shadow-sm bg-light w-100">
          <h6>แพ็คเกจยอดนิยม</h6>
          <h4 id="popular_package">-</h4>
        </div>
      </div>

      <div class="col-12 mb-3 d-flex justify-content-center">
        <div class="border rounded p-3 shadow-sm bg-light w-100">
          <h6>โปรโมชั่นยอดนิยม</h6>
          <h4 id="popular_promotion">-</h4>
        </div>
      </div>

      <div class="col-12 mb-3 d-flex justify-content-center">
        <div class="border rounded p-3 shadow-sm bg-light w-100">
          <h6>โต๊ะยอดนิยม</h6>
          <h4 id="popular_table">-</h4>
        </div>
      </div>

    </div>
  </div>
</div>

<script src="js/popular_service.js"></script>
