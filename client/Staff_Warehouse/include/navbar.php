<div class="d-flex">
    <nav id="sidebarMenu" class="col-md-3 col-lg-3 d-md-block sidebar collapse">
        <div class="position-sticky py-4 px-3 sidebar-sticky">
            <ul class="nav flex-column">
                
                <li class="nav-item">
                    <a class="nav-link" href="index.php">
                        <span><i class="fa-solid fa-list me-2"></i>หน้าหลัก</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="raw_material.php">
                        <span><i class="fa-solid fa-bowl-food me-2"></i>รายการวัตถุดิบ</span> <!--รายการอาหารวัตถุดิบ-->
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="import_raw_material.php">
                        <span><i class="fa-solid fa-clock-rotate-left me-2"></i>รายการนำเข้าวัตถุดิบ</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="package.php">
                        <span><i class="fa-solid fa-file me-2"></i>รายการแพ็คเกจ</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="categories.php">
                        <span><i class="fa-solid fa-box-archive me-2"></i>รายการหมวดหมู่</span>
                    </a>
                </li>   

                <li class="nav-item">
                    <a class="nav-link" href="supplier.php">
                        <span><i class="fa-solid fa-table-list me-2"></i>รายการซัพพลายเออร์</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="warehouse.php">
                        <span><i class="fa-solid fa-boxes-stacked me-2"></i>สถานที่เก็บวัตถุดิบ</span>
                    </a>

                <li class="nav-item">
                    <a class="nav-link" href="notification.php">
                        <span><i class="fa-solid fa-bell me-2"></i>การแจ้งเตือน</span>
                        <span id="notifyCount" class="position-absolute start-100 badge rounded-pill bg-danger">
                            0
                        </span>
                    </a>
                </li>

                <li class="nav-item border-top mt-auto pt-2">
                    <a class="nav-link" href="#">
                        <i class="bi-box-arrow-left me-2"></i>
                        Logout
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</div>
<script>
    function loadNotifyCount() {
        fetch('get_notify_count.php')
            .then(res => res.text())
            .then(count => {
                const badge = document.getElementById('notifyCount');
                badge.innerText = count;
                badge.style.display = parseInt(count) > 0 ? 'inline-block' : 'none';
            });
    }

    loadNotifyCount();
    setInterval(loadNotifyCount, 5000);
</script>