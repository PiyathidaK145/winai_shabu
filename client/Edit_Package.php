<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Package</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }

        h1 {
            text-align: center;
            margin-top: 20px;
            color: #333;
        }

        .form-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin: 30px auto;
            max-width: 800px;
        }

        .image-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr); /* กำหนดให้มี 2 คอลัมน์ */
            gap: 15px;
            margin-top: 20px;
        }

        .image-item {
            display: flex;
            align-items: center;
            position: relative;
            cursor: pointer;
            text-align: center;
        }

        .image-item input[type="checkbox"] {
            margin-right: 10px;  /* ให้ checkbox อยู่ข้างซ้ายของรูป */
        }

        .image-item img {
            max-width: 100%;
            border: 2px solid transparent;
            border-radius: 8px;
            transition: border-color 0.3s ease;
        }

        .image-item input[type="checkbox"]:checked + label img {
            border-color: #007bff; /* เปลี่ยนขอบของรูปเมื่อเลือก */
        }

        .form-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .btn-back {
            background-color:rgb(51, 52, 53);
            color: white;
        }

        .btn-back:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>
<?php include 'include/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <main class="main-wrapper col-md-9 ms-sm-auto py-4 col-lg-9 px-md-4 border-start">
            <div class="container">
                <h1>แก้ไข</h1>
                <div class="form-container">
                    <form action="add_package_process.php" method="POST" enctype="multipart/form-data">
                        <!-- Package Name -->
                        <div class="mb-3">
                            <label for="package_name" class="form-label">ชื่อแพ็คเกจ</label>
                            <input type="text" id="package_name" name="package_name" class="form-control" placeholder="Enter Package Name" required>
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="package_description" class="form-label">คำอธิบาย</label>
                            <textarea id="package_description" name="package_description" class="form-control" placeholder="Enter Description" rows="4" required></textarea>
                        </div>

                        <!-- Promotion Image Selection -->
                        <label for="promotion_image" class="form-label">เลือกเมนู</label>
                        <div class="image-grid">
                            <div class="image-item">
                                <input type="checkbox" id="image1" name="promotion_image[]" value="image1.jpg">
                                <label for="image1"><img src="image1.jpg" alt="Image 1"></label>
                            </div>
                            <div class="image-item">
                                <input type="checkbox" id="image2" name="promotion_image[]" value="image2.jpg">
                                <label for="image2"><img src="image2.jpg" alt="Image 2"></label>
                            </div>
                            <div class="image-item">
                                <input type="checkbox" id="image3" name="promotion_image[]" value="image3.jpg">
                                <label for="image3"><img src="image3.jpg" alt="Image 3"></label>
                            </div>
                            <div class="image-item">
                                <input type="checkbox" id="image4" name="promotion_image[]" value="image4.jpg">
                                <label for="image4"><img src="image4.jpg" alt="Image 4"></label>
                            </div>
                            <div class="image-item">
                                <input type="checkbox" id="image5" name="promotion_image[]" value="image5.jpg">
                                <label for="image5"><img src="image5.jpg" alt="Image 5"></label>
                            </div>
                            <div class="image-item">
                                <input type="checkbox" id="image6" name="promotion_image[]" value="image6.jpg">
                                <label for="image6"><img src="image6.jpg" alt="Image 6"></label>
                            </div>
                        </div>

                        <div class="form-buttons">
                            <a href="Package.php" class="btn btn-secondary btn-back">กลับ</a>
                            <button type="submit" class="btn btn-primary">บันทึกข้อมูล</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
