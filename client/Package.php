<?php
// สมมติฐานข้อมูลแพ็กเกจ
$packages = [
    ["id" => 1, "name" => "Basic Package", "description" => "Basic promotional package for beginners", "promotion" => "Yes"],
    ["id" => 2, "name" => "Premium Package", "description" => "Advanced promotional package with extra benefits", "promotion" => "Yes"],
    ["id" => 3, "name" => "Exclusive Package", "description" => "Exclusive package for top-tier customers", "promotion" => "No"],
];

// เริ่มต้นการกรอง
$filteredPackages = $packages;

// ตรวจสอบหากมีการกรองจาก checkbox
if (isset($_POST['filter_promotion']) && !empty($_POST['filter_promotion'])) {
    $filterPromotions = $_POST['filter_promotion'];
    $filteredPackages = array_filter($packages, function ($package) use ($filterPromotions) {
        // กรองตามโปรโมชั่นที่ตรงกับที่เลือก
        return in_array($package['promotion'], $filterPromotions);
    });
}
?>

<?php include 'include/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <main class="main-wrapper col-md-9 ms-sm-auto py-4 col-lg-9 px-md-4 border-start">
            <div class="container">
                <h1>แพ็คเกจ</h1>

                <!-- ปุ่มเพิ่มแพ็กเกจใหม่ -->
                <a href="Add_Package.php" class="btn btn-primary mb-3">เพิ่มแพ็คเกจ</a>
                <div class="mb-3 d-flex justify-content-end">
                    <button class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#filterModal">การกรอง</button>
                </div>

                <!-- ตารางแสดงแพ็กเกจ -->
                <div class="package-table">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>ชื่อแพ็คเกจ</th>
                                <th>คำอธิบาย</th>
                                <th>โปรโมชั่น</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($filteredPackages as $package) {
                                echo "<tr>";
                                echo "<td>" . $package['id'] . "</td>";
                                echo "<td>" . htmlspecialchars($package['name']) . "</td>";
                                echo "<td>" . htmlspecialchars($package['description']) . "</td>";
                                echo "<td>" . $package['promotion'] . "</td>";
                                echo "<td>
                                        <a href='Edit_Package.php?id=" . $package['id'] . "' class='btn btn-warning'>Edit</a>
                                        <button class='btn btn-danger'>Delete</button>
                                      </td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- Filter Modal -->
    <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filterModalLabel">กรองข้อมูลแพ็กเกจ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="packages.php">
                        <div class="mb-3">
                            <label class="form-label">โปรโมชั่น</label><br>
                            <input type="checkbox" id="promotion_yes" name="filter_promotion[]" value="Yes">
                            <label for="promotion_yes">มีโปรโมชั่น</label><br>
                            <input type="checkbox" id="promotion_no" name="filter_promotion[]" value="No">
                            <label for="promotion_no">ไม่มีโปรโมชั่น</label><br>
                        </div>
                        <button type="submit" class="btn btn-primary">กรอง</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
