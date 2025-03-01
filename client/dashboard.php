<?php include 'include/header.php'; ?>
<?php include 'config/connect_db.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <main class="main-wrapper col-md-9 ms-sm-auto py-4 col-lg-9 px-md-4 border-start">
                <h1 class="h2 mb-0">Dashboard</h1>
                <main class="main-content position-relative border-radius-lg ">
                    <div class="container-fluid py-4">
                        <div class="row">
                            <div class="d-flex justify-content-end mb-4">
                                <button id="boxFilterButton" class="btn btn-outline-secondary" type="button" data-bs-toggle="modal" data-bs-target="#boxFilterModal">
                                    การกรอง
                                </button>
                            </div>

                            <!-- boxFilterModal -->
                            <div class="modal fade" id="boxFilterModal" tabindex="-1" aria-labelledby="boxFilterModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="boxFilterModalLabel">กรองตามวันที่</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form id="filterForm">
                                                <div class="mb-3">
                                                    <label for="startDate" class="form-label">วันที่เริ่มต้น</label>
                                                    <input type="date" class="form-control" id="startDate" name="start_date">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="endDate" class="form-label">วันที่สิ้นสุด</label>
                                                    <input type="date" class="form-control" id="endDate" name="end_date">
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-primary" id="applyFilter">ใช้ตัวกรอง</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                                <div class="card">
                                    <div class="card-body p-3">
                                        <div class="row">
                                            <div class="col-8">
                                                <div class="numbers">
                                                    <p class="text-dark font-weight-bolder">ยอดขาย</p>
                                                    <h5 class="font-weight-bolder" id="totalSales">0 บาท</h5>
                                                </div>
                                            </div>
                                            <div class="col-4 text-end">
                                                <div class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle d-flex align-items-center justify-content-center" style="height: 50px; width: 50px;">
                                                    <i class="fa-solid fa-money-bill text-white" style="font-size: 24px;"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                                <div class="card">
                                    <div class="card-body p-3">
                                        <div class="row">
                                            <div class="col-8">
                                                <div class="numbers">
                                                    <p class="text-dark font-weight-bolder">จำนวนลูกค้า</p>
                                                    <h5 class="font-weight-bolder" id="totalCustomers">0</h5>
                                                </div>
                                            </div>
                                            <div class="col-4 text-end">
                                                <div class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle d-flex align-items-center justify-content-center" style="height: 50px; width: 50px;">
                                                    <i class="fa-solid fa-person text-white" style="font-size: 24px;"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                                <div class="card">
                                    <div class="card-body p-3">
                                        <div class="row">
                                            <div class="col-8">
                                                <!--ต้นทุนทั้งหมด-->
                                                <div class="numbers">
                                                    <div class="numbers">
                                                        <p class="text-dark font-weight-bolder">ต้นทุนรวม</p>
                                                        <h5 class="font-weight-bolder" id="totalCost">0 บาท</h5>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-4 text-end">
                                                <div class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle d-flex align-items-center justify-content-center" style="height: 50px; width: 50px;">
                                                    <i class="fa-solid fa-money-bill-transfer text-white" style="font-size: 24px;"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-sm-6">
                                <div class="card">
                                    <div class="card-body p-3">
                                        <div class="row">
                                            <div class="col-8">
                                                <!--กำไรที่ได้-->
                                                <div class="numbers">
                                                    <p class="text-dark font-weight-bolder">กำไร</p>
                                                    <h5 class="font-weight-bolder text-success" id="totalProfit">0 บาท</h5>
                                                </div>
                                            </div>
                                            <div class="col-4 text-end">
                                                <div class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle d-flex align-items-center justify-content-center" style="height: 50px; width: 50px;">
                                                    <i class="fa-solid fa-hand-holding-dollar text-white" style="font-size: 24px;"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <!-- ภาพรวมยอดขายและภาพรวมคลังสินค้า -->
                            <div class="col-lg-7 mb-lg-0 mb-4">
                                <div class="card z-index-2 h-100">
                                    <div class="card-header pb-3 pt-3 d-flex justify-content-between align-items-center">
                                        <select class="form-select" id="overviewDropdown" onchange="handleOverviewChange(this.value)">
                                            <option value="sales">ภาพรวมยอดขาย</option>
                                            <option value="inventory">ภาพรวมคลังสินค้า</option>
                                        </select>
                                    </div>
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-end">
                                            <button id="overviewFilterButton" class="btn btn-outline-secondary" type="button" data-bs-toggle="modal" data-bs-target="#overviewFilterModal_for_sales">
                                                การกรอง
                                            </button>
                                        </div>
                                        <div class="chart">
                                            <canvas id="chart-line" class="chart-canvas" height="300"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Filter Modals สำหรับยอดขายและคลังสินค้า -->
                            <div class="modal fade" id="overviewFilterModal_for_sales" tabindex="-1" aria-labelledby="overviewFilterModalSalesLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="overviewFilterModalSalesLabel">กรองสำหรับภาพรวมยอดขาย</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form id="overviewFilterFormSales">
                                                <div class="mb-3">
                                                    <label for="startDateSales" class="form-label">เลือกช่วงเวลา</label>
                                                    <input type="date" class="form-control" id="startDateSales">
                                                    <input type="date" class="form-control mt-2" id="endDateSales">
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                                            <button type="button" class="btn btn-primary" onclick="applyOverviewFilter('sales')">ใช้ Filter</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal fade" id="overviewFilterModal_for_inventory" tabindex="-1" aria-labelledby="overviewFilterModalInventoryLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="overviewFilterModalInventoryLabel">กรองสำหรับภาพรวมคลังสินค้า</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form id="overviewFilterFormInventory">
                                                <div class="mb-3">
                                                    <label for="startDateInventory" class="form-label">เลือกช่วงเวลา</label>
                                                    <input type="date" class="form-control" id="startDateInventory">
                                                    <input type="date" class="form-control mt-2" id="endDateInventory">
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                                            <button type="button" class="btn btn-primary" onclick="applyOverviewFilter('inventory')">ใช้ Filter</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <script>
                                function handleOverviewChange(value) {
                                    updateFilterModal(value); // อัปเดต modal
                                    changeChart(value); // เปลี่ยนข้อมูลกราฟ
                                }

                                function updateFilterModal(value) {
                                    const filterButton = document.getElementById("overviewFilterButton");
                                    const modalId = value === "sales" ?
                                        "#overviewFilterModal_for_sales" :
                                        "#overviewFilterModal_for_inventory";
                                    filterButton.setAttribute("data-bs-target", modalId);
                                }

                                function changeChart(value) {
                                    const ctx = document.getElementById('chart-line').getContext('2d');
                                    const data = value === "sales" ? [10, 20, 30, 40] : [50, 40, 30, 20];
                                    const labels = ['สัปดาห์ที่ 1', 'สัปดาห์ที่ 2', 'สัปดาห์ที่ 3', 'สัปดาห์ที่ 4'];

                                    if (window.chart) {
                                        window.chart.destroy();
                                    }
                                    window.chart = new Chart(ctx, {
                                        type: 'line',
                                        data: {
                                            labels: labels,
                                            datasets: [{
                                                label: value === "sales" ? 'ภาพรวมยอดขาย' : 'ภาพรวมคลังสินค้า',
                                                data: data,
                                                borderColor: 'rgba(75, 192, 192, 1)',
                                                borderWidth: 2
                                            }]
                                        },
                                        options: {
                                            responsive: true,
                                            plugins: {
                                                legend: {
                                                    display: true
                                                }
                                            }
                                        }
                                    });
                                }

                                function applyOverviewFilter(type) {
                                    let startDate, endDate;

                                    if (type === "sales") {
                                        startDate = document.getElementById('startDateSales').value;
                                        endDate = document.getElementById('endDateSales').value;
                                    } else if (type === "inventory") {
                                        startDate = document.getElementById('startDateInventory').value;
                                        endDate = document.getElementById('endDateInventory').value;
                                    }


                                    alert(`Filter applied for ${type}: ${startDate} - ${endDate}`);

                                    const data = type === "sales" ? [15, 25, 35, 45] : [45, 35, 25, 15];

                                    changeChart(type);
                                }
                                // ค่าเริ่มต้น
                                document.addEventListener("DOMContentLoaded", function() {
                                    handleOverviewChange("sales"); // ตั้งค่าเริ่มต้นเป็นยอดขาย
                                });
                            </script>
                            <!--จบภาพรวมยอดขาย ภาพรวมคลังสินค้า-->

                            <!--top 3 section (promotion,package,table)-->
                            <div class="col-lg-5">
                                <div class="card bg-dark text-white">
                                    <div class="card-header pb-0 pt-4 d-flex justify-content-between align-items-center m-0">
                                        <div class="row w-100 m-0">
                                            <!-- Dropdown -->
                                            <div class="col p-0 me-2">
                                                <select class="form-select bg-dark text-white" id="overviewDropdown" onchange="handleSelectionChange(this.value)">
                                                    <option value="promotions">โปรโมชัน</option>
                                                    <option value="packages">แพ็คเกจ</option>
                                                    <option value="tables">โต๊ะ</option>
                                                </select>
                                            </div>
                                            <!-- Button -->
                                            <div class="col-auto p-0">
                                                <button id="filterButton" class="btn btn-outline-light" type="button" data-bs-toggle="modal" data-bs-target="#filterModal_for_promotions">
                                                    การกรอง
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body p-3">
                                        <ul id="top3List" class="list-group">
                                            <!-- Default Top 3 Items -->
                                            <li class="list-group-item d-flex justify-content-between align-items-center mb-2" style="border-radius: 15px; background-color: #f8f9fa;">
                                                <div class="d-flex align-items-center">
                                                    <img src="images/bg1.jpg" class="rounded-circle me-3" alt="top 1" style="width: 50px; height: 50px; object-fit: cover;">
                                                    <div class="d-flex flex-column">
                                                        <h6 class="mb-1">Top 1</h6>
                                                        <span class="text-xs">Default 1</span>
                                                    </div>
                                                </div>
                                                <button class="btn btn-link btn-sm"><i class="ni ni-bold-right"></i></button>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Filter Modals -->
                            <div class="modal fade" id="filterModal_for_promotions" tabindex="-1" aria-labelledby="filterModalPromotionsLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="filterModalPromotionsLabel">กรองสำหรับโปรโมชัน</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form id="filterFormPromotions">
                                                <!-- Filters for Promotions -->
                                                <div class="mb-3">
                                                    <label class="form-label">ระบุประเภท</label>
                                                    <input type="text" class="form-control" id="filterPromotionsInput">
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                                            <button type="button" class="btn btn-primary" onclick="applyFilter('promotions')">ใช้ Filter</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal fade" id="filterModal_for_packages" tabindex="-1" aria-labelledby="filterModalPackagesLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="filterModalPackagesLabel">กรองสำหรับแพ็คเกจ</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form id="filterFormPackages">
                                                <!-- Filters for Packages -->
                                                <div class="mb-3">
                                                    <label class="form-label">ระบุชื่อแพ็คเกจ</label>
                                                    <input type="text" class="form-control" id="filterPackagesInput">
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                                            <button type="button" class="btn btn-primary" onclick="applyFilter('packages')">ใช้ Filter</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal fade" id="filterModal_for_tables" tabindex="-1" aria-labelledby="filterModalTablesLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="filterModalTablesLabel">กรองสำหรับโต๊ะ</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form id="filterFormTables">
                                                <!-- Filters for Tables -->
                                                <div class="mb-3">
                                                    <label class="form-label">ระบุหมายเลขโต๊ะ</label>
                                                    <input type="text" class="form-control" id="filterTablesInput">
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                                            <button type="button" class="btn btn-primary" onclick="applyFilter('tables')">ใช้ Filter</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!--จบ top 3 section (promotion,package,table)-->
                        </div>

                </main>
        </div>
        <?php include 'include/footer.php'; ?>