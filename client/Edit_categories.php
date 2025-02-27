<?php include 'include/header.php'; ?>
<div class="container-fluid">
    <div class="row">
        <main class="main-wrapper col-md-9 ms-sm-auto py-4 col-lg-9 px-md-4 border-start">
            <div class="container">
                <h1 class="text-center my-4">แก้ไข</h1>
                <div class="form-container mx-auto">
                    <form action="add_category_process.php" method="POST">
                        <div class="mb-3">
                            <input type="text" name="category_name" class="form-control" placeholder="Category Name" required>
                        </div>
                        <div class="mb-3">
                            <textarea name="category_description" class="form-control" placeholder="Description" rows="4" required></textarea>
                        </div>
                        <div class="form-buttons">
                            <a href="Categories.php" class="btn btn-secondary btn-back">กลับ</a>
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