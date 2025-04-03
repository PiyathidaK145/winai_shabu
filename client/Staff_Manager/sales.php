<?php include 'include/header.php';
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=daily_sales_report.xls");
header("Pragma: no-cache");
header("Expires: 0");

// Define menu items and categories dynamically
$categories = [
    "เมนูทั้งหมด" => ["น้ำดำ", "ต้มยำ", "น้ำใส", "หม่าล่า", "เนื้อออสเตรเลีย", "เนื้อวากิว", "ผักกาดขาว", "ข้าวโพดอ่อน", "กุ้ง", "หมึก", "มาม่า", "เต้าหู้ปลา", "แอปเปิล", "แตงโม", "น้ำอัดลม", "ไอติม"],
    "น้ำซุป" => ["น้ำดำ", "ต้มยำ", "น้ำใส", "หม่าล่า"],
    "เนื้อ" => ["เนื้อออส", "เนื้อวากิว"],
    "ผัก" => ["ผักกาดขาว", "ข้าวโพดอ่อน"],
    "ทะเล" => ["กุ้ง", "หมึก"],
    "อาหารแปรรูป" => ["มาม่า", "เต้าหู้ปลา"],
    "ผลไม้" => ["แอปเปิล", "แตงโม"],
    "อื่นๆ" => ["น้ำอัดลม", "ไอติม"],
];

// Sample data for demonstration
$sampleData = [
    ['C001', 'สมชาย ใจดี', 'ชาย', 'ใช่', '45 นาที', [1, 0, 0, 1, 2, 0, 1, 1, 0, 0, 1, 0, 1, 1, 0, 1], 'เนื้อหมู', '80', 'เงินสด', '2024-12-23 12:00:00'],
    ['C002', 'สมหญิง สวยงาม', 'หญิง', 'ไม่ใช่', '30 นาที', [0, 1, 0, 0, 1, 2, 1, 0, 1, 1, 0, 2, 1, 0, 0, 1], 'ทะเล', '100', 'บัตรเครดิต', '2024-12-24 12:30:00'],
    ['C003', 'เจมส์ บอนด์', 'ชาย', 'ใช่', '60 นาที', [0, 0, 0, 1, 1, 1, 1, 2, 1, 0, 0, 1, 1, 2, 1, 0], 'เนื้อวัว', '120', 'โอนผ่านมือถือ', '2024-12-24 13:00:00'],
    ['C004', 'สมหญิง จิตดี', 'หญิง', 'ใช่', '50 นาที', [1, 0, 1, 0, 2, 1, 1, 0, 0, 1, 1, 0, 1, 1, 0, 1], 'รวม', '140', 'เงินสด', '2024-12-23 14:30:00'],
    ['C005', 'สมชาย ขยันดี', 'ชาย', 'ไม่ใช่', '35 นาที', [0, 1, 1, 0, 0, 2, 1, 0, 1, 1, 1, 2, 0, 0, 1, 1], 'มังสาวิรัติ', '110', 'บัตรเครดิต', '2024-12-24 11:30:00'],
    ['C006', 'จุฬา รักษ์สิ่งแวดล้อม', 'หญิง', 'ใช่', '60 นาที', [0, 1, 0, 1, 1, 1, 1, 2, 0, 0, 1, 1, 1, 2, 1, 1], 'ทะเล', '150', 'โอนผ่านมือถือ', '2024-12-24 15:00:00'],
    ['C007', 'สมศักดิ์ มั่นคง', 'ชาย', 'ใช่', '55 นาที', [1, 0, 0, 0, 2, 0, 1, 1, 0, 1, 2, 1, 0, 1, 0, 0], 'เนื้อหมู', '130', 'เงินสด', '2024-12-25 13:45:00'],
    ['C008', 'สมศรี ทองแท้', 'หญิง', 'ไม่ใช่', '25 นาที', [0, 1, 0, 0, 0, 2, 1, 0, 0, 1, 0, 2, 1, 0, 0, 1], 'ผลไม้', '90', 'บัตรเครดิต', '2024-12-26 10:15:00'],
    ['C009', 'วิชัย บุญส่ง', 'ชาย', 'ใช่', '40 นาที', [1, 0, 0, 0, 1, 0, 1, 0, 1, 0, 2, 1, 0, 1, 1, 0], 'เนื้อวัว', '150', 'เงินสด', '2024-12-27 18:20:00'],
    ['C010', 'สมปราชญ์ คงมั่น', 'ชาย', 'ไม่ใช่', '20 นาที', [0, 1, 1, 1, 0, 2, 1, 0, 1, 1, 1, 0, 0, 0, 1, 1], 'มังสาวิรัติ', '100', 'บัตรเครดิต', '2024-12-28 14:10:00']
];

?>

<div class="container-fluid">
    <div class="row">
        <main class="main-wrapper col-md-9 ms-sm-auto py-4 col-lg-9 px-md-4 border-start">
            <div class="title-group mb-3"></div>
            <h1 class="h2 mb-0">รายงานยอดขายรายวัน</h1>

            <div class="mb-3 d-flex justify-content-end">
                <button class="btn btn-dark me-2">ส่งออกไฟล์ Excel</button>
                <button class="btn btn-dark me-2">ส่งออกไฟล์ CSV</button>
                <button type="button" class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#filterModal">การกรอง</button>
            </div>


            <!-- Filter Modal -->
            <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="filterModalLabel">ตัวเลือกการกรอง</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">

                            <div class="row">
                                <!-- Date Selection -->
                                <div class="col-md-6 mb-4">
                                    <div class="card p-3">
                                        <strong><label for="dateSelect" class="form-label">เลือกวันที่:</label></strong>
                                        <input type="date" id="dateSelect" class="form-control">
                                        <button type="button" class="btn btn-primary mt-2" onclick="addDate()">เพิ่มวันที่</button>
                                        <div id="dateList" class="mt-3"></div>
                                    </div>
                                </div>

                                <!-- Time Range Selection -->
                                <div class="col-md-6 mb-4">
                                    <div class="card p-3">
                                        <strong><label for="startTime" class="form-label">เลือกช่วงเวลา:</label></strong>
                                        <div class="row mb-2">
                                            <div class="col">
                                                <input type="time" id="startTime" class="form-control" placeholder="Start Time">
                                            </div>
                                            <div class="col">
                                                <input type="time" id="endTime" class="form-control" placeholder="End Time">
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-primary" onclick="addTimeRange()">เพิ่มช่วงเวลา</button>
                                        <div id="timeList" class="mt-3"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <!-- เพศ -->
                                    <div class="card h-100 p-3">
                                        <strong><label class="form-label">เพศ:</label></strong>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="genderselectAll" onchange="toggleAllCheckboxes(this, '.genderCheckbox')">
                                            <label for="genderselectAll" class="form-check-label text-dark">ทั้งหมด</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="genderCheckbox form-check-input" id="male" value="ชาย" onchange="updateSelectAll('.genderCheckbox', 'genderselectAll')">
                                            <label for="male" class="form-check-label text-dark">ชาย</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="genderCheckbox form-check-input" id="female" value="หญิง" onchange="updateSelectAll('.genderCheckbox', 'genderselectAll')">
                                            <label for="female" class="form-check-label text-dark">หญิง</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="genderCheckbox form-check-input" id="other" value="อื่นๆ" onchange="updateSelectAll('.genderCheckbox', 'genderselectAll')">
                                            <label for="other" class="form-check-label text-dark">อื่นๆ</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <!-- ช่วงอายุ -->
                                    <div class="card h-100 p-3">
                                        <strong><label class="form-label">ช่วงอายุ:</label></strong>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="ageselectAll" onchange="toggleAllCheckboxes(this, '.ageCheckbox')">
                                            <label for="ageselectAll" class="form-check-label text-dark">ทั้งหมด</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="ageCheckbox form-check-input" id="<15" value="<15" onchange="updateSelectAll('.ageCheckbox', 'ageselectAll')">
                                            <label for="<15" class="form-check-label text-dark">น้อยกว่า 15</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="ageCheckbox form-check-input" id="15-25" value="15-25" onchange="updateSelectAll('.ageCheckbox', 'ageselectAll')">
                                            <label for="15-25" class="form-check-label text-dark">15-25</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="ageCheckbox form-check-input" id="26-35" value="26-35" onchange="updateSelectAll('.ageCheckbox', 'ageselectAll')">
                                            <label for="26-35" class="form-check-label text-dark">26-35</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="ageCheckbox form-check-input" id="36-45" value="36-45" onchange="updateSelectAll('.ageCheckbox', 'ageselectAll')">
                                            <label for="36-45" class="form-check-label text-dark">36-45</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="ageCheckbox form-check-input" id="46-55" value="46-55" onchange="updateSelectAll('.ageCheckbox', 'ageselectAll')">
                                            <label for="46-55" class="form-check-label text-dark">46-55</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="ageCheckbox form-check-input" id=">55" value=">55" onchange="updateSelectAll('.ageCheckbox', 'ageselectAll')">
                                            <label for=">55" class="form-check-label text-dark">55 ขึ้นไป</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <!-- ศาสนา -->
                                    <div class="card h-100 p-3">
                                        <strong><label class="form-label">ศาสนา:</label></strong>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="religionselectAll" onchange="toggleAllCheckboxes(this, '.religionCheckbox')">
                                            <label for="religionselectAll" class="form-check-label text-dark">ทั้งหมด</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="religionCheckbox form-check-input" id="Buddhist" value="พุทธ" onchange="updateSelectAll('.religionCheckbox', 'religionselectAll')">
                                            <label for="Buddhist" class="form-check-label text-dark">พุทธ</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="religionCheckbox form-check-input" id="Christ" value="คริสต์" onchange="updateSelectAll('.religionCheckbox', 'religionselectAll')">
                                            <label for="Christ" class="form-check-label text-dark">คริสต์</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="religionCheckbox form-check-input" id="Islam" value="อิสลาม" onchange="updateSelectAll('.religionCheckbox', 'religionselectAll')">
                                            <label for="Islam" class="form-check-label text-dark">อิสลาม</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="religionCheckbox form-check-input" id="otherReligion" value="อื่นๆ" onchange="updateSelectAll('.religionCheckbox', 'religionselectAll')">
                                            <label for="otherReligion" class="form-check-label text-dark">อื่นๆ</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <!-- สมาชิก -->
                                    <div class="card h-100 p-3">
                                        <strong><label class="form-label">สมาชิก:</label></strong>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="memberselectAll" onchange="toggleAllCheckboxes(this, '.memberCheckbox')">
                                            <label for="memberselectAll" class="form-check-label text-dark">ทั้งหมด</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="memberCheckbox form-check-input" id="member" value="เป็นสมาชิก" onchange="updateSelectAll('.memberCheckbox', 'memberselectAll')">
                                            <label for="member" class="form-check-label text-dark">เป็นสมาชิก</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="memberCheckbox form-check-input" id="notMember" value="ไม่เป็นสมาชิก" onchange="updateSelectAll('.memberCheckbox', 'memberselectAll')">
                                            <label for="notMember" class="form-check-label text-dark">ไม่เป็นสมาชิก</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- "เมนูทั้งหมด" -->
                                <div class="col-md-12 mb-3">
                                    <div class="card p-3 h-100">
                                        <label class="form-label fw-bold text-dark">เมนูทั้งหมด</label>
                                        <div>
                                            <input type="checkbox" id="selectAllMenus" class="form-check-input" onchange="toggleAllMenus()">
                                            <label for="selectAllMenus" class="form-check-label text-dark">ทั้งหมด</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Categories -->
                                <?php foreach ($categories as $category => $items): ?>
                                    <?php if ($category !== "เมนูทั้งหมด"): ?>
                                        <div class="col-md-3 mb-3">
                                            <div class="card p-3 h-100">
                                                <label class="form-label fw-bold text-dark"><?php echo ucfirst($category); ?></label>
                                                <div>
                                                    <input type="checkbox" id="<?php echo $category; ?>SelectAll" class="form-check-input" onchange="toggleAllCheckboxes(this, '.<?php echo $category; ?>Checkbox')">
                                                    <label for="<?php echo $category; ?>SelectAll" class="form-check-label text-dark">ทั้งหมด</label>
                                                </div>
                                                <?php foreach ($items as $item): ?>
                                                    <div>
                                                        <input type="checkbox" class="<?php echo $category; ?>Checkbox form-check-input menuCheckbox" id="<?php echo $item; ?>" value="<?php echo $item; ?>" onchange="updateSelectAll('.<?php echo $category; ?>Checkbox', '<?php echo $category; ?>SelectAll')">
                                                        <label for="<?php echo $item; ?>" class="form-check-label text-dark"><?php echo $item; ?></label>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>

                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                            <button type="button" class="btn btn-primary" onclick="applyFilters()">ยืนยัน</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- โต๊ะ -->
            <div class="dropdown mb-4">
                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    เลือกโต๊ะ
                </button>
                <div class="dropdown-menu p-3" aria-labelledby="dropdownMenuButton">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="tableSelectAll" onchange="toggleAllTables(this)">
                        <label class="form-check-label" for="tableSelectAll">เลือกทั้งหมด</label>
                    </div>
                    <?php for ($i = 1; $i <= 20; $i++): ?>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input tableCheckbox" id="table<?php echo $i; ?>" value="โต๊ะ <?php echo $i; ?>" onchange="updateSelectedTables()">
                            <label class="form-check-label text-dark" for="table<?php echo $i; ?>">โต๊ะ <?php echo $i; ?></label>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>
            <p id="selectedTables" class="mb-4 text-dark">โต๊ะที่เลือก: ไม่มี</p>

            <!-- summary display -->
            <div class="row">
                <div class="col-md-3 mb-3">
                    <div class="card p-3">
                        <label for="totalPriceDisplay" class="form-label fw-bold">ยอดขายรวมทั้งหมด:</label>
                        <label id="totalPriceDisplay" class="form-label">0.00บาท</label>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card p-3">
                        <label for="totalCustomersDisplay" class="form-label fw-bold">จำนวนลูกค้าทั้งหมด:</label>
                        <label id="totalCustomersDisplay" class="form-label">0</label>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card p-3">
                        <label for="bestSellingPackage" class="form-label fw-bold">แพ็คเกจที่ขายดีที่สุด:</label>
                        <label id="bestSellingPackage" class="form-label">#</label>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card p-3">
                        <label for="topRevenueTable" class="form-label fw-bold">โต๊ะที่สร้างรายได้สูงสุด:</label>
                        <label id="topRevenueTable" class="form-label">#</label>
                    </div>
                </div>
            </div>

            <!-- Table Display -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table--bs-secondary-bg">
                        <tr id="tableHeader">
                            <th>ลำดับ</th>
                            <th>รหัสลูกค้า</th>
                            <th>ชื่อ</th>
                            <th>เพศ</th>
                            <th>เป็นสมาชิก</th>
                            <th>เวลาที่ใช้ในการทาน</th>
                            <?php foreach ($categories["เมนูทั้งหมด"] as $menu): ?>
                                <th data-category="<?php echo $menu; ?>"><?php echo $menu; ?></th>
                            <?php endforeach; ?>
                            <th>แพ็คเกจ</th>
                            <th>ราคา</th>
                            <th>ช่องทางการชำระเงิน</th>
                            <th>ประทับเวลา</th>
                        </tr>
                    </thead>
                    <tbody id="salesTableBody">
                        <?php foreach ($sampleData as $row): ?>
                            <tr class="table-<?php echo strtolower(str_replace(' ', '-', str_replace('Table ', '', $row[8]))); ?>">
                                <td class="row-index"></td> <!-- ลำดับ -->
                                <td><?php echo $row[0]; ?></td> <!-- รหัสลูกค้า -->
                                <td><?php echo $row[1]; ?></td> <!-- ชื่อ -->
                                <td><?php echo $row[2]; ?></td> <!-- เพศ -->
                                <td><?php echo $row[3]; ?></td> <!-- เป็นสมาชิก -->
                                <td><?php echo $row[4]; ?></td> <!-- เวลาที่ใช้ในการทาน -->

                                <?php foreach ($categories["เมนูทั้งหมด"] as $menu): ?> <!-- เมนู -->
                                    <td><?php
                                        // Check if the menu exists in the row data
                                        $menuIndex = array_search($menu, $categories["เมนูทั้งหมด"]);
                                        echo isset($row[5][$menuIndex]) ? $row[5][$menuIndex] : 0;
                                        ?></td>
                                <?php endforeach; ?>

                                <td><?php echo $row[6]; ?></td> <!-- แพ็คเกจ -->
                                <td><?php echo $row[7]; ?></td> <!-- ราคา -->
                                <td><?php echo $row[8]; ?></td> <!-- ช่องทางการชำระเงิน -->
                                <td><?php echo $row[9]; ?></td> <!-- ประทับเวลา -->
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <p id="noDataMessage" class="no-data" style="display: none;">No data available for this table.</p>
            </div>
    </div>

    <?php include 'include/footer.php'; ?>

    <script>
        const categories = <?php echo json_encode($categories); ?>;
        let activeCategories = ["เมนูทั้งหมด"]; // Default to all categories
        let filters = {
            date: null,
            timeRange: {
                start: null,
                end: null
            },
            gender: [],
            age: [],
            religion: [],
            member: [],
            table: [],
        };

        // Helper functions to get selected filter values
        function getCheckedValues(selector) {
            return Array.from(document.querySelectorAll(selector))
                .filter(checkbox => checkbox.checked)
                .map(checkbox => checkbox.value);
        }

        function applyFilters() {
            console.log("applyFilters called");

            // ตรวจสอบว่ามีค่าในแต่ละ input หรือไม่
            filters.date = document.getElementById('dateSelect')?.value || null;
            filters.timeRange.start = document.getElementById('startTime')?.value || null;
            filters.timeRange.end = document.getElementById('endTime')?.value || null;
            filters.gender = getCheckedValues('.genderCheckbox');
            filters.age = getCheckedValues('.ageCheckbox');
            filters.religion = getCheckedValues('.religionCheckbox');
            filters.member = getCheckedValues('.memberCheckbox');
            filters.table = getCheckedValues('.tableCheckbox');
            activeCategories = getCheckedValues('.menuCheckbox');

            // หากไม่มี activeCategories ให้ใช้ค่าเริ่มต้น
            if (activeCategories.length === 0) {
                activeCategories = ["เมนูทั้งหมด"];
            }

            // Debugging log
            console.log("Filters:", filters);
            console.log("Active Categories:", activeCategories);

            filterData();

            // ปิด modal
            const filterModal = document.getElementById('filterModal');
            if (filterModal) {
                const modalInstance = bootstrap.Modal.getInstance(filterModal);
                modalInstance?.hide();
            }
        }

        function toggleCategory(selectedCategory) {
            // Toggle the selected category
            if (selectedCategory === "ทั้งหมด") {
                activeCategories = ["ทั้งหมด"];
            } else {
                if (activeCategories.includes(selectedCategory)) {
                    activeCategories = activeCategories.filter(cat => cat !== selectedCategory); // Remove if already selected
                } else {
                    if (activeCategories.includes("ทั้งหมด")) {
                        activeCategories = [selectedCategory]; // Replace "ทั้งหมด" with specific categories
                    } else {
                        activeCategories.push(selectedCategory); // Add new category
                    }
                }
            }

            updateCategoryButtons();
            filterData();
        }

        function toggleAllMenus() {
            const selectAllCheckbox = document.getElementById('selectAllMenus');
            const menuCheckboxes = document.querySelectorAll('.menuCheckbox');

            menuCheckboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });

            // Update "ทั้งหมด" ในแต่ละ category
            const categorySelectAllCheckboxes = document.querySelectorAll('input[id$="SelectAll"]');
            categorySelectAllCheckboxes.forEach(categoryCheckbox => {
                categoryCheckbox.checked = selectAllCheckbox.checked;
                categoryCheckbox.indeterminate = false;
            });
        }

        function updateCategoryButtons() {
            // Update button styles based on activeCategories
            const buttons = document.querySelectorAll('.category-button');
            buttons.forEach(button => {
                if (activeCategories.includes(button.dataset.category)) {
                    button.classList.add('btn-dark');
                    button.classList.remove('btn-secondary');
                } else {
                    button.classList.remove('btn-dark');
                    button.classList.add('btn-secondary');
                }
            });
        }


        function filterData() {
            console.log("Active Categories:", activeCategories); // Debugging
            console.log("Categories Object:", categories); // Debugging

            // ตรวจสอบว่า activeCategories มี category ที่ถูกต้อง
            const validCategories = activeCategories.filter(category => {
                if (!categories[category]) {
                    console.error(`Category '${category}' does not exist in categories.`);
                    return false;
                }
                if (!Array.isArray(categories[category])) {
                    console.error(`Category '${category}' is not an array.`);
                    return false;
                }
                return true;
            });

            if (validCategories.length === 0) {
                console.error("No valid categories found for filtering.");
                return;
            }

            let selectedItems = [];
            if (validCategories.includes("เมนูทั้งหมด")) {
                selectedItems = categories["เมนูทั้งหมด"];
            } else {
                validCategories.forEach(category => {
                    selectedItems = [...new Set([...selectedItems, ...categories[category]])];
                });
            }

            const rows = document.querySelectorAll('#salesTableBody tr');
            const allColumns = document.querySelectorAll('th[data-category]');
            const staticColumnsCount = 5; // จำนวนคอลัมน์ที่คงที่

            let visibleRowCount = 0;

            rows.forEach(row => {
                const cells = row.children;
                const table = cells[1]?.textContent.trim();
                const gender = cells[3]?.textContent.trim();
                const member = cells[4]?.textContent.trim();
                const timeStamp = cells[9]?.textContent.trim();
                const rowDate = timeStamp ? new Date(timeStamp).toISOString().split('T')[0] : '';
                const rowTime = timeStamp ? timeStamp.split(' ')[1] : '';
                const menuQuantities = Array.from(cells).slice(staticColumnsCount, cells.length - 3);

                const matchesTable = filters.table.length === 0 || filters.table.includes(table);
                const matchesDate = !filters.date || rowDate === filters.date;
                const matchesTime = (!filters.timeRange.start || rowTime >= filters.timeRange.start) &&
                    (!filters.timeRange.end || rowTime <= filters.timeRange.end);
                const matchesGender = filters.gender.length === 0 || filters.gender.includes(gender);
                const matchesMember = filters.member.length === 0 || filters.member.includes(member);
                const matchesMenus = menuQuantities.some((cell, index) => {
                    return selectedItems.includes(allColumns[index + staticColumnsCount]?.textContent.trim()) && cell.textContent !== '0';
                });

                const matchesAll = matchesTable && matchesDate && matchesTime && matchesGender && matchesMember && matchesMenus;

                if (matchesAll) {
                    row.style.display = '';
                    visibleRowCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            console.log("Visible Rows Count:", visibleRowCount);

            const noDataMessage = document.getElementById('noDataMessage');
            noDataMessage.style.display = visibleRowCount === 0 ? 'block' : 'none';

            if (visibleRowCount === 0) {
                document.getElementById('totalPriceDisplay').textContent = `0.00 บาท`;
                document.getElementById('totalCustomersDisplay').textContent = `0 คน`;
            } else {
                updateRowNumbers();
                calculateTotalPrice();
                updateCustomerCount();
            }
        }


        function updateRowNumbers() {
            const rows = document.querySelectorAll('#salesTableBody tr');
            let currentIndex = 1;

            rows.forEach(row => {
                const indexCell = row.querySelector('.row-index');
                if (row.style.display !== 'none') {
                    indexCell.textContent = currentIndex++;
                } else {
                    indexCell.textContent = '';
                }
            });
        }

        function calculateTotalPrice() {
    const rows = document.querySelectorAll('#salesTableBody tr');
    let totalPrice = 0;

    rows.forEach(row => {
        if (row.style.display !== 'none') {
            const priceCell = row.querySelector('td:nth-last-child(4)');
            const price = parseFloat(priceCell?.textContent.replace('บาท', '').trim()) || 0;
            totalPrice += price;
        }
    });

    document.getElementById('totalPriceDisplay').textContent = `${totalPrice.toFixed(2)} บาท`;
}

function updateCustomerCount() {
    const rows = document.querySelectorAll('#salesTableBody tr');
    const visibleCount = Array.from(rows).filter(row => row.style.display !== 'none').length;
    document.getElementById('totalCustomersDisplay').textContent = `${visibleCount} คน`;
}

        document.addEventListener("DOMContentLoaded", () => {
            // Select the 'selectAllMenus' checkbox
            const selectAllMenus = document.getElementById('selectAllMenus');

            // Check if the element exists before adding an event listener
            if (selectAllMenus) {
                selectAllMenus.addEventListener('change', toggleAllMenus);
            } else {
                console.error("Element with ID 'selectAllMenus' not found.");
            }

            // Select all table checkboxes
            const tableCheckboxes = document.querySelectorAll('.tableCheckbox');
            if (tableCheckboxes.length > 0) {
                tableCheckboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', applyFilters);
                });
            } else {
                console.error("No elements with class 'tableCheckbox' found.");
            }

            // Select all menu checkboxes
            const menuCheckboxes = document.querySelectorAll('.menuCheckbox');
            if (menuCheckboxes.length > 0) {
                menuCheckboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', applyFilters);
                });
            } else {
                console.error("No elements with class 'menuCheckbox' found.");
            }
        });


        const dateList = [];

        function addDate() {
            const dateInput = document.getElementById('dateSelect');
            const selectedDate = dateInput.value;

            if (selectedDate && !dateList.includes(selectedDate)) { // Prevent duplicates
                dateList.push(selectedDate);
                updateDateList();
                dateInput.value = ''; // Clear input
            } else if (!selectedDate) {
                alert("กรุณาเลือกวันที่ก่อน");
            } else {
                alert("วันที่นี้ได้ถูกเพิ่มแล้ว");
            }
        }

        function updateDateList() {
            const listElement = document.getElementById('dateList');
            listElement.innerHTML = ''; // ล้างรายการก่อน

            dateList.forEach((date, index) => {
                const dateItem = document.createElement('div');
                dateItem.className = 'date-item';
                dateItem.textContent = date;

                // สร้างปุ่มกากบาท
                const removeButton = document.createElement('button');
                removeButton.textContent = '✖';
                removeButton.className = 'btn btn-danger btn-sm ms-2';
                removeButton.onclick = () => removeDate(index); // กำหนดฟังก์ชันลบวันที่

                dateItem.appendChild(removeButton);
                listElement.appendChild(dateItem);
            });

            // แสดงวันที่ที่เลือกใน span
            document.getElementById('selectedDates').textContent = dateList.join(', ');
        }

        function removeDate(index) {
            dateList.splice(index, 1); // ลบวันที่ที่เลือก
            updateDateList(); // อัปเดตรายการ
        }

        const timeRanges = [];

        function addTimeRange() {
            const startTimeInput = document.getElementById('startTime');
            const endTimeInput = document.getElementById('endTime');
            const startTime = startTimeInput.value;
            const endTime = endTimeInput.value;

            if (startTime && endTime) {
                timeRanges.push({
                    start: startTime,
                    end: endTime
                });
                updateTimeList();
                startTimeInput.value = ''; // ล้างค่า input หลังจากเพิ่ม
                endTimeInput.value = ''; // ล้างค่า input หลังจากเพิ่ม
            } else {
                alert("กรุณาเลือกช่วงเวลาเริ่มต้นและสิ้นสุด");
            }
        }

        function updateTimeList() {
            const listElement = document.getElementById('timeList');
            listElement.innerHTML = ''; // ล้างรายการก่อน

            timeRanges.forEach((range, index) => {
                const timeItem = document.createElement('div');
                timeItem.className = 'time-item';
                timeItem.textContent = `เริ่ม: ${range.start} - สิ้นสุด: ${range.end}`;

                // สร้างปุ่มกากบาท
                const removeButton = document.createElement('button');
                removeButton.textContent = '✖';
                removeButton.className = 'btn btn-danger btn-sm ms-2';
                removeButton.onclick = () => removeTimeRange(index); // กำหนดฟังก์ชันลบช่วงเวลา

                timeItem.appendChild(removeButton);
                listElement.appendChild(timeItem);
            });
        }

        function removeTimeRange(index) {
            timeRanges.splice(index, 1); // ลบช่วงเวลาที่เลือก
            updateTimeList(); // อัปเดตรายการ
        }

        function updateSelectAll(checkboxClass, selectAllId) {
            const checkboxes = document.querySelectorAll(checkboxClass);
            const selectAllCheckbox = document.getElementById(selectAllId);
            selectAllCheckbox.checked = Array.from(checkboxes).every(checkbox => checkbox.checked);
        }

        function toggleAllCheckboxes(selectAllCheckbox, checkboxClass) {
            const checkboxes = document.querySelectorAll(checkboxClass);
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked; // ตั้งค่า checkbox อื่นๆ ตามสถานะของ checkbox "ทั้งหมด"
            });

            // อัปเดตสถานะ filters หรือ activeCategories
            if (checkboxClass.includes('menuCheckbox')) {
                applyFilters();
            }
        }


        function toggleAllTables(selectAllCheckbox) {
            const tableCheckboxes = document.querySelectorAll('.tableCheckbox');
            tableCheckboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
            updateSelectedTables();
        }

        function updateSelectedTables() {
            const selectedTables = [];
            const tableCheckboxes = document.querySelectorAll('.tableCheckbox');

            tableCheckboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    selectedTables.push(checkbox.value);
                }
            });

            const selectedTablesDisplay = document.getElementById('selectedTables');
            if (selectedTables.length > 0) {
                selectedTablesDisplay.textContent = `โต๊ะที่เลือก: ${selectedTables.join(', ')}`;
            } else {
                selectedTablesDisplay.textContent = 'โต๊ะที่เลือก: ไม่มี';
            }

            // อัปเดตสถานะ "เลือกทั้งหมด" ตามการเลือก
            const selectAllCheckbox = document.getElementById('tableSelectAll');
            selectAllCheckbox.checked = selectedTables.length === tableCheckboxes.length;
        }
    </script>
    </body>

    </html>