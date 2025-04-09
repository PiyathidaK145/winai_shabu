<div class="d-flex">
    <nav id="sidebarMenu" class="col-md-3 col-lg-3 d-md-block sidebar collapse">
        <div class="position-sticky py-4 px-3 sidebar-sticky">
            <ul class="nav flex-column">
                
                <li class="nav-item">
<<<<<<< HEAD
                    <a class="nav-link" href="index.php">
                        <span><i class="fa-solid fa-list me-2"></i>รายการอาหาร</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="raw_material.php">
                        <span><i class="fa-solid fa-bowl-food me-2"></i>รายการวัตถุดิบ</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="import_raw_material.php">
                        <span><i class="fa-solid fa-clock-rotate-left me-2"></i>รายการนำเข้าวัตถุดิบ</span>
                    </a>

                <li class="nav-item">
                    <a class="nav-link" href="notification.php">
                        <span><i class="fa-solid fa-bell me-2"></i>การแจ้งเตือน</span>
                        <span id="notifyCount" class="position-absolute start-100 badge rounded-pill bg-danger">
                            0
                        </span>
                    </a>
                </li>

=======
                    <a class="nav-link" href="table_order_list.php">
                        <span><i class="fa-solid fa-list me-2"></i>รายการอาหารแค่ละโต๊ะ</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="raw_material.php">
                        <span><i class="fa-solid fa-bowl-food me-2"></i>รายการวัตถุดิบ</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="import_raw_material.php">
                        <span><i class="fa-solid fa-clock-rotate-left me-2"></i>รายการนำเข้าวัตถุดิบ</span>
                    </a>

>>>>>>> 8b2216fd18008dad437930077b67c9ef256e13d2
                <li class="nav-item border-top mt-auto pt-2">
                    <a class="nav-link" href="#">
                        <i class="bi-box-arrow-left me-2"></i>
                        Logout
                    </a>
                </li>
            </ul>
        </div>
    </nav>
<<<<<<< HEAD
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
=======
</div>
>>>>>>> 8b2216fd18008dad437930077b67c9ef256e13d2
