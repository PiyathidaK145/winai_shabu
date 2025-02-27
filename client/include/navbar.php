<div class="d-flex">
    <nav id="sidebarMenu" class="col-md-3 col-lg-3 d-md-block sidebar collapse">
        <div class="position-sticky py-4 px-3 sidebar-sticky">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">
                        <span><i class="fa-solid fa-house me-2"></i> ภาพรวม</span> <!-- หน้าสถิติ -->
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="table_order_list.php">
                        <span><i class="fa-solid fa-list me-2"></i> รายการอาหารอาหารแต่ละโต๊ะ</span> <!-- หน้ารายการอาหารแต่ละโต๊ะ -->
                    </a>
                </li>

                <!-- ยอดขาย -->
                <a class="nav-link collapsed d-flex justify-content-between align-items-center" href="#"
                    data-bs-toggle="collapse"
                    data-bs-target="#collapseSale" aria-expanded="false" aria-controls="collapseSale">
                    <span><i class="fa-solid fa-money-bill me-2"></i> ยอดขาย</span>
                    <i class="fas fa-angle-down"></i>
                </a>

                <div class="collapse" id="collapseSale" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav flex-column">
                        <a class="nav-link" href="daily_sales.php">รายวัน</a>
                        <a class="nav-link" href="#">รายเดือน</a>
                        <a class="nav-link" href="#">รายปี</a>
                    </nav>
                </div>

                <!-- คลังสินค้า -->
                <a class="nav-link collapsed d-flex justify-content-between align-items-center" href="#"
                    data-bs-toggle="collapse"
                    data-bs-target="#collapseWarehouse" aria-expanded="false" aria-controls="collapseWarehouse">
                    <span><i class="fa-solid fa-warehouse me-2"></i> คลังสินค้า</span>
                    <i class="fas fa-angle-down"></i>
                </a>

                <div class="collapse" id="collapseWarehouse" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav flex-column">
                        <a class="nav-link" href="#">สต็อกสินค้า</a>
                        <a class="nav-link" href="#">ระบบคำนวณต้นทุนกำไร</a>
                        <a class="nav-link" href="#">ระบบเปรียบเทียบราคา</a>
                    </nav>
                </div>

                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <span><i class="fa-solid fa-comment me-2"></i> รายการคอมเมนต์</span> <!-- แสดงรายการ comment -->
                    </a>
                </li>

                <!-- Promotion -->
                <a class="nav-link collapsed d-flex justify-content-between align-items-center" href="#"
                    data-bs-toggle="collapse"
                    data-bs-target="#collapsePromotion" aria-expanded="false" aria-controls="collapsePromotion">
                    <span><i class="fa-solid fa-rectangle-ad me-2"></i> โปรโมชัน</span>
                    <i class="fas fa-angle-down"></i>
                </a>

                <div class="collapse" id="collapsePromotion" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav flex-column">
                        <a class="nav-link" href="Promotion.php">รายการโปรโมชัน</a>
                        <a class="nav-link" href="Add_promotion.php">เพิ่มโปรโมชัน</a>
                    </nav>
                </div>

                <!-- Package -->
                <a class="nav-link collapsed d-flex justify-content-between align-items-center" href="#"
                    data-bs-toggle="collapse"
                    data-bs-target="#collapsePackage" aria-expanded="false" aria-controls="collapsePackage">
                    <span><i class="fa-regular fa-note-sticky me-2"></i> แพ็คเกจ</span>
                    <i class="fas fa-angle-down"></i>
                </a>

                <div class="collapse" id="collapsePackage" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav flex-column">
                        <a class="nav-link" href="Package.php">รายการแพ็คเกจ</a>
                        <a class="nav-link" href="Add_Package.php">เพิ่มแพ็คเกจ</a>
                    </nav>
                </div>

                <!-- Category -->
                <a class="nav-link collapsed d-flex justify-content-between align-items-center" href="#"
                    data-bs-toggle="collapse"
                    data-bs-target="#collapseCategory" aria-expanded="false" aria-controls="collapseCategory">
                    <span><i class="fa-solid fa-table-list me-2"></i> ประเภทอาหาร</span>
                    <i class="fas fa-angle-down"></i>
                </a>

                <div class="collapse" id="collapseCategory" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav flex-column">
                        <a class="nav-link" href="Categories.php">รายการประเภทอาหาร</a>
                        <a class="nav-link" href="Edit_categories.php">เพิ่มประเภทอาหาร</a>
                    </nav>
                </div>

                <!-- Menu -->
                <a class="nav-link collapsed d-flex justify-content-between align-items-center" href="#"
                    data-bs-toggle="collapse"
                    data-bs-target="#collapseMenu" aria-expanded="false" aria-controls="collapseMenu">
                    <span><i class="fa-solid fa-bowl-food me-2"></i> เมนูอาหาร</span>
                    <i class="fas fa-angle-down"></i>
                </a>

                <div class="collapse" id="collapseMenu" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav flex-column">
                        <a class="nav-link" href="#">รายการเมนูอาหาร</a>
                        <a class="nav-link" href="#">เพิ่มเมนูอาหาร</a>
                    </nav>
                </div>

                <!-- Staff -->
                <a class="nav-link collapsed d-flex justify-content-between align-items-center" href="#"
                    data-bs-toggle="collapse"
                    data-bs-target="#collapseStaff" aria-expanded="false" aria-controls="collapseStaff">
                    <span><i class="fa-solid fa-person me-2"></i> พนักงาน</span>
                    <i class="fas fa-angle-down"></i>
                </a>

                <div class="collapse" id="collapseStaff" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav flex-column">
                        <a class="nav-link" href="Staff.php">รายการพนักงาน</a>
                        <a class="nav-link" href="Add_Staff.php">เพิ่มพนักงาน</a>
                    </nav>
                </div>

                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <span><i class="fa-solid fa-user me-2"></i> ลูกค้าที่สมัครเป็นสมาชิก</span> <!-- หน้ารายชื่อลูกค้าที่สมัครเป็นสมาชิก -->
                    </a>
                </li>

                <!-- <li class="nav-item featured-box mt-lg-5 mt-4 mb-4">
                <img src="images/credit-card.png" class="img-fluid" alt="">

                <a class="btn custom-btn" href="#">Upgrade</a>
            </li> -->
                <li class="nav-item border-top mt-auto pt-2">
                    <a class="nav-link" href="#">
                        <i class="bi-box-arrow-left me-2"></i>
                        Logout
                    </a>
                </li>
            </ul>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const toggleButton = document.getElementById('toggleSidebar'); // ปุ่มสำหรับเปิด/ปิดเมนู
                const sidebarMenu = document.getElementById('sidebarMenu');

                toggleButton.addEventListener('click', function() {
                    sidebarMenu.classList.toggle('show'); // สลับคลาส 'show'
                });
            });
        </script>
    </nav>

</div>