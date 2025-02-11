<!DOCTYPE html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Winai's Shabu</title>

    <!-- CSS FILES -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- <link href="https://fonts.googleapis.com/css2?family=Unbounded:wght@300;400;700&display=swap" rel="stylesheet"> -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-icons.css" rel="stylesheet">
    <link href="css/apexcharts.css" rel="stylesheet">
    <link href="css/tooplate-mini-finance.css" rel="stylesheet">
    <!-- fontawesome -->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>

<body>
    <header class="navbar sticky-top flex-md-nowrap">
        <div class="col-md-3 col-lg-3 me-0 px-3 fs-6">
            <a class="navbar-brand" href="index.php">
                <i class="bi-box"></i>
                Winai's Shabu
            </a>
        </div>

        <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- <form class="custom-form header-form ms-lg-3 ms-md-3 me-lg-auto me-md-auto order-2 order-lg-0 order-md-0" action="#" method="get" role="form">
            <input class="form-control" name="search" type="text" placeholder="Search" aria-label="Search">
        </form> -->

        <div class="navbar-nav me-lg-2">
            <div class="nav-item text-nowrap d-flex align-items-center">
                <div class="dropdown px-3">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="images/medium-shot-happy-man-smiling.jpg" class="profile-image img-fluid" alt="">  <!--รูปทดสอบ-->
                    </a>
                    <ul class="dropdown-menu bg-white shadow">
                        <li>
                            <div class="dropdown-menu-profile-thumb d-flex">
                                <img src="images/medium-shot-happy-man-smiling.jpg" class="profile-image img-fluid me-3" alt=""> <!--รูปทดสอบ-->

                                <div class="d-flex flex-column">
                                    <small>Thomas</small> <!--อย่าลืมเปลี่ยนชื่อและemailนะ-->
                                    <a href="#">thomas@site.com</a>
                                </div>
                            </div>
                        </li>

                        <li>
                            <a class="dropdown-item" href="profile.html">
                                <i class="bi-person me-2"></i>
                                โปรไฟล์
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item" href="setting.html">
                                <i class="bi-gear me-2"></i>
                                ตั้งค่า
                            </a>
                        </li> 

                        <!--<li>
                            <a class="dropdown-item" href="help-center.html">
                                <i class="bi-question-circle me-2"></i>
                                Help
                            </a>
                        </li>-->

                        <li class="border-top mt-3 pt-2 mx-4">
                            <a class="dropdown-item ms-0 me-0" href="#">
                                <i class="bi-box-arrow-left me-2"></i>
                                ออกจากระบบ
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </header>
    <?php include 'navbar.php'; ?>