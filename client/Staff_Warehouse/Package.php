<?php
date_default_timezone_set("Asia/Bangkok");
include dirname(__FILE__) . '/include/header.php';
include dirname(__FILE__) . '/../../config/connect_db.php';
$sql = "
    SELECT 
        p.package_id,
        p.package_name,
        p.discription,
        p.price,
        GROUP_CONCAT(r.item_name SEPARATOR ', ') AS menu_list
    FROM 
        package AS p
    LEFT JOIN package_item AS pi ON p.package_id = pi.package_id
    LEFT JOIN menu AS m ON pi.menu_id = m.menu_id
    LEFT JOIN raw_material AS r ON m.raw_material_id = r.raw_material_id
    GROUP BY 
        p.package_id
";


$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>รายการแพ็คเกจ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="row">
        <main class="main-wrapper col-md-9 ms-sm-auto py-4 col-lg-9 px-md-4 border-start">
            <div class="container mt-4">
                <h3 class="mb-4"><strong>รายการแพ็คเกจ</strong></h3>
                <div class="mb-3 text-end">
                    <a href="add_package.php" class="btn btn-success">
                        <i class="fa-solid fa-plus"></i>
                    </a>
                </div>
                <div class="table-responsive">
                    <table id="tableUse" class="table_use table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ลำดับ</th>
                                <th>ชื่อเพ็คเกจ</th>
                                <th>คำอธิบาย</th>
                                <th onclick="sortTableByNumber()" style="cursor: pointer;">
                                    ราคา <i id="sortIcon" class="fa-solid fa-arrow-down"></i>
                                </th>
                                <th>การดำเนินการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (mysqli_num_rows($result) > 0):
                                $no = 1;
                                while ($row = mysqli_fetch_assoc($result)):
                            ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= htmlspecialchars($row['package_name']) ?></td>
                                        <td><?= htmlspecialchars($row['discription']) ?></td>
                                        <td data-capacity="<?= $row['price'] ?>"><?= number_format($row['price'], 2) ?> บาท</td>

                                        <td>
                                            <a href="edit_package.php?id=<?= $row['package_id'] ?>" class="btn btn-sm btn-warning">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <a href="delete_package.php?id=<?= $row['package_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบแพ็คเกจนี้?')">
                                                <i class="fa-solid fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile;
                            else: ?>
                                <tr>
                                    <td colspan="6" class="text-center">ไม่พบข้อมูลแพ็คเกจ</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <script>
                let sortAsc = true;

                function sortTableByNumber() {
                    const table = document.getElementById("tableUse");
                    const tbody = table.querySelector("tbody");
                    const rows = Array.from(tbody.querySelectorAll("tr"));

                    rows.sort((a, b) => {
                        const aCap = parseFloat(a.cells[3].dataset.capacity || 0);
                        const bCap = parseFloat(b.cells[3].dataset.capacity || 0);
                        return sortAsc ? aCap - bCap : bCap - aCap;
                    });

                    tbody.innerHTML = "";
                    rows.forEach(row => tbody.appendChild(row));

                    // เปลี่ยนไอคอน
                    const icon = document.getElementById("sortIcon");
                    icon.classList.remove("fa-arrow-down", "fa-arrow-up");
                    icon.classList.add(sortAsc ? "fa-arrow-up" : "fa-arrow-down");

                    sortAsc = !sortAsc;
                }
            </script>


            <?php include 'include/footer.php'; ?>