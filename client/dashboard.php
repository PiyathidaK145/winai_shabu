<?php include 'include/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <main class="main-wrapper col-md-9 ms-sm-auto py-4 col-lg-9 px-md-4 border-start">
            <h1 class="h2 mb-0">Dashboard</h1>
            <main class="main-content position-relative border-radius-lg ">

                <div class="container-fluid py-4">
                    <div class="row">
                        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                            <div class="card">
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-8">
                                            <!--ยอดขาย-->
                                            <div class="numbers">
                                                <p class="text-dark font-weight-bolder">ยอดขาย</p>
                                                <h5 class="font-weight-bolder">
                                                    $53,000
                                                </h5>
                                                <!--ในช่วงที่ผ่านมาเติบโตขึ้นกี่เปอร์เซ็นต์-->
                                                <p class="mb-0">
                                                    <span class="text-success text-sm font-weight-bolder">+55%</span>
                                                </p>
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
                                            <!--จำนวนลูกค้า-->
                                            <div class="numbers">
                                                <p class="text-dark font-weight-bolder">จำนวนลูกค้า</p>
                                                <h5 class="font-weight-bolder">
                                                    2,300
                                                </h5>
                                                <!--ในช่วงที่ผ่านมาจำนวนลูกค้าเพิ่มขึ้นกี่เปอร์เซ็นต์-->
                                                <p class="mb-0">
                                                    <span class="text-success text-sm font-weight-bolder">+3%</span>
                                                </p>
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
                                                <p class="text-dark font-weight-bolder">ต้นทุน</p>
                                                <h5 class="font-weight-bolder">
                                                    +3,462
                                                </h5>
                                                <!--ในช่วงที่ผ่านมาใช้ต้นทุนในการซื้อสินค้าเพิ่มขึ้นกี่เปอร์เซ็นต์-->
                                                <p class="mb-0">
                                                    <span class="text-danger text-sm font-weight-bolder">-2%</span>
                                                </p>
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
                                                <h5 class="font-weight-bolder">
                                                    $103,430
                                                </h5>
                                                <!--ในช่วงที่ผ่านมาได้กำไรเพิ่มขึ้นกี่เปอร์เซ็นต์-->
                                                <p class="mb-0">
                                                    <span class="text-success text-sm font-weight-bolder">+5%</span>
                                                </p>
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

                        <script>
                            // Handle selection change for dropdown
                            function handleSelectionChange(value) {
                                updateFilterModal(value);
                                updateTop3(value);
                            }

                            // Update filter modal target
                            function updateFilterModal(value) {
                                const filterButton = document.getElementById("filterButton");
                                const modalId = value === "promotions" ?
                                    "#filterModal_for_promotions" :
                                    value === "packages" ?
                                    "#filterModal_for_packages" :
                                    "#filterModal_for_tables";
                                filterButton.setAttribute("data-bs-target", modalId);
                            }

                            // Update Top 3 list
                            function updateTop3(value) {
                                const top3List = document.getElementById("top3List");
                                let top3Data = [];

                                if (value === "promotions") {
                                    top3Data = [{
                                            title: "โปรโมชัน 1",
                                            subtitle: "xxx"
                                        },
                                        {
                                            title: "โปรโมชัน 2",
                                            subtitle: "yyy"
                                        },
                                        {
                                            title: "โปรโมชัน 3",
                                            subtitle: "zzz"
                                        },
                                    ];
                                } else if (value === "packages") {
                                    top3Data = [{
                                            title: "แพ็คเกจ 1",
                                            subtitle: "aaa"
                                        },
                                        {
                                            title: "แพ็คเกจ 2",
                                            subtitle: "bbb"
                                        },
                                        {
                                            title: "แพ็คเกจ 3",
                                            subtitle: "ccc"
                                        },
                                    ];
                                } else if (value === "tables") {
                                    top3Data = [{
                                            title: "โต๊ะ 1",
                                            subtitle: "111"
                                        },
                                        {
                                            title: "โต๊ะ 2",
                                            subtitle: "222"
                                        },
                                        {
                                            title: "โต๊ะ 3",
                                            subtitle: "333"
                                        },
                                    ];
                                }

                                // Clear the current list
                                top3List.innerHTML = "";

                                // Render new Top 3 items
                                top3Data.forEach((item) => {
                                    const li = document.createElement("li");
                                    li.className = "list-group-item d-flex justify-content-between align-items-center mb-2";
                                    li.style = "border-radius: 15px; background-color: #f8f9fa;";
                                    li.innerHTML = `
                <div class="d-flex align-items-center">
                    <img src="images/bg1.jpg" class="rounded-circle me-3" alt="${item.title}" style="width: 50px; height: 50px; object-fit: cover;">
                    <div class="d-flex flex-column">
                        <h6 class="mb-1">${item.title}</h6>
                        <span class="text-xs">${item.subtitle}</span>
                    </div>
                </div>
                <button class="btn btn-link btn-sm"><i class="ni ni-bold-right"></i></button>
            `;
                                    top3List.appendChild(li);
                                });
                            }

                            // Apply filter (dummy logic)
                            function applyFilter(type) {
                                alert(`Filter applied for ${type}`);
                                // Update top 3 data based on filter logic
                                updateTop3(type);
                            }

                            // Default selection
                            handleSelectionChange("promotions");
                        </script>
                        <!--จบ top 3 section (promotion,package,table)-->
                    </div>


                    <div class="col-lg-5">
                        <div class="card card-carousel overflow-hidden h-100 p-0">
                            <div id="carouselExampleCaptions" class="carousel slide h-100" data-bs-ride="carousel">
                                <div class="carousel-inner border-radius-lg h-100">
                                    <div class="carousel-item h-100 active" style="background-image: url('images/carousel-1.jpg');
      background-size: cover;">
                                        <div class="carousel-caption d-none d-md-block bottom-0 text-start start-0 ms-5">
                                            <div class="icon icon-shape icon-sm bg-white text-center border-radius-md mb-3">
                                                <i class="ni ni-camera-compact text-dark opacity-10"></i>
                                            </div>
                                            <h5 class="text-white mb-1">Get started with Argon</h5>
                                            <p>There’s nothing I really wanted to do in life that I wasn’t able to get good at.</p>
                                        </div>
                                    </div>
                                    <div class="carousel-item h-100" style="background-image: url('../assets/img/carousel-2.jpg');
      background-size: cover;">
                                        <div class="carousel-caption d-none d-md-block bottom-0 text-start start-0 ms-5">
                                            <div class="icon icon-shape icon-sm bg-white text-center border-radius-md mb-3">
                                                <i class="ni ni-bulb-61 text-dark opacity-10"></i>
                                            </div>
                                            <h5 class="text-dark mb-1">Faster way to create web pages</h5>
                                            <p>That’s my skill. I’m not really specifically talented at anything except for the ability to learn.</p>
                                        </div>
                                    </div>
                                    <div class="carousel-item h-100" style="background-image: url('../assets/img/carousel-3.jpg');
      background-size: cover;">
                                        <div class="carousel-caption d-none d-md-block bottom-0 text-start start-0 ms-5">
                                            <div class="icon icon-shape icon-sm bg-white text-center border-radius-md mb-3">
                                                <i class="ni ni-trophy text-dark opacity-10"></i>
                                            </div>
                                            <h5 class="text-dark mb-1">Share with us your design tips!</h5>
                                            <p>Don’t be afraid to be wrong because you can’t learn anything from a compliment.</p>
                                        </div>
                                    </div>
                                </div>
                                <button class="carousel-control-prev w-5 me-3" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next w-5 me-3" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-lg-7 mb-lg-0 mb-4">
                        <div class="card ">
                            <div class="card-header pb-0 p-3">
                                <div class="d-flex justify-content-between">
                                    <h6 class="mb-2">Sales by Country</h6>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table align-items-center ">
                                    <tbody>
                                        <tr>
                                            <td class="w-30">
                                                <div class="d-flex px-2 py-1 align-items-center">
                                                    <div>
                                                        <img src="../assets/img/icons/flags/US.png" alt="Country flag">
                                                    </div>
                                                    <div class="ms-4">
                                                        <p class="text-xs font-weight-bold mb-0">Country:</p>
                                                        <h6 class="text-sm mb-0">United States</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-center">
                                                    <p class="text-xs font-weight-bold mb-0">Sales:</p>
                                                    <h6 class="text-sm mb-0">2500</h6>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-center">
                                                    <p class="text-xs font-weight-bold mb-0">Value:</p>
                                                    <h6 class="text-sm mb-0">$230,900</h6>
                                                </div>
                                            </td>
                                            <td class="align-middle text-sm">
                                                <div class="col text-center">
                                                    <p class="text-xs font-weight-bold mb-0">Bounce:</p>
                                                    <h6 class="text-sm mb-0">29.9%</h6>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="w-30">
                                                <div class="d-flex px-2 py-1 align-items-center">
                                                    <div>
                                                        <img src="../assets/img/icons/flags/DE.png" alt="Country flag">
                                                    </div>
                                                    <div class="ms-4">
                                                        <p class="text-xs font-weight-bold mb-0">Country:</p>
                                                        <h6 class="text-sm mb-0">Germany</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-center">
                                                    <p class="text-xs font-weight-bold mb-0">Sales:</p>
                                                    <h6 class="text-sm mb-0">3.900</h6>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-center">
                                                    <p class="text-xs font-weight-bold mb-0">Value:</p>
                                                    <h6 class="text-sm mb-0">$440,000</h6>
                                                </div>
                                            </td>
                                            <td class="align-middle text-sm">
                                                <div class="col text-center">
                                                    <p class="text-xs font-weight-bold mb-0">Bounce:</p>
                                                    <h6 class="text-sm mb-0">40.22%</h6>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="w-30">
                                                <div class="d-flex px-2 py-1 align-items-center">
                                                    <div>
                                                        <img src="../assets/img/icons/flags/GB.png" alt="Country flag">
                                                    </div>
                                                    <div class="ms-4">
                                                        <p class="text-xs font-weight-bold mb-0">Country:</p>
                                                        <h6 class="text-sm mb-0">Great Britain</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-center">
                                                    <p class="text-xs font-weight-bold mb-0">Sales:</p>
                                                    <h6 class="text-sm mb-0">1.400</h6>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-center">
                                                    <p class="text-xs font-weight-bold mb-0">Value:</p>
                                                    <h6 class="text-sm mb-0">$190,700</h6>
                                                </div>
                                            </td>
                                            <td class="align-middle text-sm">
                                                <div class="col text-center">
                                                    <p class="text-xs font-weight-bold mb-0">Bounce:</p>
                                                    <h6 class="text-sm mb-0">23.44%</h6>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="w-30">
                                                <div class="d-flex px-2 py-1 align-items-center">
                                                    <div>
                                                        <img src="../assets/img/icons/flags/BR.png" alt="Country flag">
                                                    </div>
                                                    <div class="ms-4">
                                                        <p class="text-xs font-weight-bold mb-0">Country:</p>
                                                        <h6 class="text-sm mb-0">Brasil</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-center">
                                                    <p class="text-xs font-weight-bold mb-0">Sales:</p>
                                                    <h6 class="text-sm mb-0">562</h6>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-center">
                                                    <p class="text-xs font-weight-bold mb-0">Value:</p>
                                                    <h6 class="text-sm mb-0">$143,960</h6>
                                                </div>
                                            </td>
                                            <td class="align-middle text-sm">
                                                <div class="col text-center">
                                                    <p class="text-xs font-weight-bold mb-0">Bounce:</p>
                                                    <h6 class="text-sm mb-0">32.14%</h6>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!--
                    <div class="col-lg-5">
                        <div class="card">
                            <div class="card-header pb-0 p-3">
                                <h6 class="mb-0">Categories</h6>
                            </div>
                            <div class="card-body p-3">
                                <ul class="list-group">
                                    <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                        <div class="d-flex align-items-center">
                                            <div class="icon icon-shape icon-sm me-3 bg-gradient-dark shadow text-center">
                                                <i class="ni ni-mobile-button text-white opacity-10"></i>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <h6 class="mb-1 text-dark text-sm">Devices</h6>
                                                <span class="text-xs">250 in stock, <span class="font-weight-bold">346+ sold</span></span>
                                            </div>
                                        </div>
                                        <div class="d-flex">
                                            <button class="btn btn-link btn-icon-only btn-rounded btn-sm text-dark icon-move-right my-auto"><i class="ni ni-bold-right" aria-hidden="true"></i></button>
                                        </div>
                                    </li>
                                    <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                        <div class="d-flex align-items-center">
                                            <div class="icon icon-shape icon-sm me-3 bg-gradient-dark shadow text-center">
                                                <i class="ni ni-tag text-white opacity-10"></i>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <h6 class="mb-1 text-dark text-sm">Tickets</h6>
                                                <span class="text-xs">123 closed, <span class="font-weight-bold">15 open</span></span>
                                            </div>
                                        </div>
                                        <div class="d-flex">
                                            <button class="btn btn-link btn-icon-only btn-rounded btn-sm text-dark icon-move-right my-auto"><i class="ni ni-bold-right" aria-hidden="true"></i></button>
                                        </div>
                                    </li>
                                    <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                        <div class="d-flex align-items-center">
                                            <div class="icon icon-shape icon-sm me-3 bg-gradient-dark shadow text-center">
                                                <i class="ni ni-box-2 text-white opacity-10"></i>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <h6 class="mb-1 text-dark text-sm">Error logs</h6>
                                                <span class="text-xs">1 is active, <span class="font-weight-bold">40 closed</span></span>
                                            </div>
                                        </div>
                                        <div class="d-flex">
                                            <button class="btn btn-link btn-icon-only btn-rounded btn-sm text-dark icon-move-right my-auto"><i class="ni ni-bold-right" aria-hidden="true"></i></button>
                                        </div>
                                    </li>
                                    <li class="list-group-item border-0 d-flex justify-content-between ps-0 border-radius-lg">
                                        <div class="d-flex align-items-center">
                                            <div class="icon icon-shape icon-sm me-3 bg-gradient-dark shadow text-center">
                                                <i class="ni ni-satisfied text-white opacity-10"></i>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <h6 class="mb-1 text-dark text-sm">Happy users</h6>
                                                <span class="text-xs font-weight-bold">+ 430</span>
                                            </div>
                                        </div>
                                        <div class="d-flex">
                                            <button class="btn btn-link btn-icon-only btn-rounded btn-sm text-dark icon-move-right my-auto"><i class="ni ni-bold-right" aria-hidden="true"></i></button>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>-->
                </div>
    </div>
    </main>
</div>
<?php include 'include/footer.php'; ?>

<!--   Core JS Files   -->
<script src="js/core/popper.min.js"></script>
<script src="js/core/bootstrap.min.js"></script>
<script src="js/plugins/perfect-scrollbar.min.js"></script>
<script src="js/plugins/smooth-scrollbar.min.js"></script>
<script src="js/plugins/chartjs.min.js"></script>
<script>
    var ctx1 = document.getElementById("chart-line").getContext("2d");

    var gradientStroke1 = ctx1.createLinearGradient(0, 230, 0, 50);

    gradientStroke1.addColorStop(1, 'rgba(94, 114, 228, 0.2)');
    gradientStroke1.addColorStop(0.2, 'rgba(94, 114, 228, 0.0)');
    gradientStroke1.addColorStop(0, 'rgba(94, 114, 228, 0)');
    new Chart(ctx1, {
        type: "line",
        data: {
            labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            datasets: [{
                label: "Mobile apps",
                tension: 0.4,
                borderWidth: 0,
                pointRadius: 0,
                borderColor: "#5e72e4",
                backgroundColor: gradientStroke1,
                borderWidth: 3,
                fill: true,
                data: [50, 40, 300, 220, 500, 250, 400, 230, 500],
                maxBarThickness: 6

            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false,
                }
            },
            interaction: {
                intersect: false,
                mode: 'index',
            },
            scales: {
                y: {
                    grid: {
                        drawBorder: false,
                        display: true,
                        drawOnChartArea: true,
                        drawTicks: false,
                        borderDash: [5, 5]
                    },
                    ticks: {
                        display: true,
                        padding: 10,
                        color: '#fbfbfb',
                        font: {
                            size: 11,
                            family: "Open Sans",
                            style: 'normal',
                            lineHeight: 2
                        },
                    }
                },
                x: {
                    grid: {
                        drawBorder: false,
                        display: false,
                        drawOnChartArea: false,
                        drawTicks: false,
                        borderDash: [5, 5]
                    },
                    ticks: {
                        display: true,
                        color: '#ccc',
                        padding: 20,
                        font: {
                            size: 11,
                            family: "Open Sans",
                            style: 'normal',
                            lineHeight: 2
                        },
                    }
                },
            },
        },
    });
</script>
<script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
        var options = {
            damping: '0.5'
        }
        Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
</script>
<!-- Github buttons -->
<script async defer src="https://buttons.github.io/buttons.js"></script>
<!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
<script src="js/argon-dashboard.min.js?v=2.1.0"></script>