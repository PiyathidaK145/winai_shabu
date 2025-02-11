<?php
include 'include/header.php';

// Simulating a sample database update process
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['orderId'], $_POST['newStatus'])) {
    $orderId = $_POST['orderId'];
    $newStatus = $_POST['newStatus'];

    // Here, you would update the database with the new status
    // Simulating a successful update response
    echo json_encode(['success' => true, 'message' => 'Status updated successfully.']);
    exit;
}
?>

<div class="container-fluid">
    <div class="row">
        <main class="main-wrapper col-md-9 ms-sm-auto py-4 col-lg-9 px-md-4 border-start">
            <div class="title-group mb-3"></div>
            <h1 class="h2 mb-0">รายการอาหารแต่ละโต๊ะ</h1>

            <!-- Table Selection Dropdown -->
            <div class="card mb-4 p-3">
                <label for="tableSelect" class="form-label">เลือกโต๊ะ:</label>
                <select id="tableSelect" class="form-select" onchange="filterTable()">
                    <option value="all">All Tables</option>
                    <?php for ($i = 1; $i <= 20; $i++): ?>
                        <option value="table-<?php echo $i; ?>">Table <?php echo $i; ?></option>
                    <?php endfor; ?>
                </select>
            </div>

            <!-- Table Display -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Order ID</th>
                            <th>Item Name</th>
                            <th>Quantity</th>
                            <th>Order Time</th>
                            <th>Status</th>
                            <th>Table Number</th>
                        </tr>
                    </thead>
                    <tbody id="orderTableBody">
                        <?php
                        $sampleData = [
                            ['1', 'O001', 'น้ำดำ', 2, '2024-12-24 12:00:00', 'Served', 'table-1'],
                            ['2', 'O002', 'ต้มยำ', 1, '2024-12-24 12:05:00', 'Preparing', 'table-2'],
                            ['3', 'O003', 'น้ำใส', 3, '2024-12-24 12:10:00', 'Pending', 'table-3'],
                            ['4', 'O004', 'หม่าล่า', 4, '2024-12-24 12:15:00', 'Served', 'table-4'],
                            ['5', 'O005', 'เนื้อออสเตเรีย', 2, '2024-12-24 12:20:00', 'Preparing', 'table-1'],
                            ['6', 'O006', 'เนื้อวากิว', 1, '2024-12-24 12:25:00', 'Pending', 'table-6'],
                            ['7', 'O007', 'ผักกาดขาว', 3, '2024-12-24 12:30:00', 'Served', 'table-7'],
                        ];

                        foreach ($sampleData as $row) {
                            echo "<tr class='{$row[6]}'>";
                            echo "<td>{$row[0]}</td>"; // #
                            echo "<td>{$row[1]}</td>"; // Order ID
                            echo "<td>{$row[2]}</td>"; // Item Name
                            echo "<td>{$row[3]}</td>"; // Quantity
                            echo "<td>{$row[4]}</td>"; // Order Time
                            echo "<td>
                                    <select class='status-select' data-order-id='{$row[1]}'>
                                        <option value='Served'" . ($row[5] === 'Served' ? ' selected' : '') . ">Served</option>
                                        <option value='Preparing'" . ($row[5] === 'Preparing' ? ' selected' : '') . ">Preparing</option>
                                        <option value='Pending'" . ($row[5] === 'Pending' ? ' selected' : '') . ">Pending</option>
                                    </select>
                                  </td>";
                            echo "<td>" . ucfirst(str_replace('table-', 'Table ', $row[6])) . "</td>"; // Table Number
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <p id="noDataMessage" class="no-data" style="display: none;">No data available for this table.</p>
            </div>
        </main>
    </div>

    <script>
        function filterTable() {
            const selectedTable = document.getElementById('tableSelect').value;
            const rows = document.querySelectorAll('#orderTableBody tr');
            let visibleRowCount = 0;

            rows.forEach(row => {
                if (selectedTable === 'all' || row.classList.contains(selectedTable.toLowerCase())) {
                    row.style.display = '';
                    visibleRowCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            const noDataMessage = document.getElementById('noDataMessage');
            noDataMessage.style.display = visibleRowCount === 0 ? 'block' : 'none';

            updateRowNumbers();
        }

        function updateRowNumbers() {
            const rows = document.querySelectorAll('#orderTableBody tr');
            let currentIndex = 1;

            rows.forEach(row => {
                if (row.style.display !== 'none') {
                    const indexCell = row.querySelector('td:first-child');
                    if (indexCell) {
                        indexCell.textContent = currentIndex++;
                    }
                }
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            updateRowNumbers();

            // Add event listeners to status dropdowns
            document.querySelectorAll('.status-select').forEach(select => {
                select.addEventListener('change', event => {
                    const orderId = event.target.getAttribute('data-order-id');
                    const newStatus = event.target.value;

                    // Update status via AJAX
                    fetch('current_file.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: `orderId=${orderId}&newStatus=${newStatus}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Status updated successfully!');
                        } else {
                            alert('Failed to update status.');
                        }
                    })
                    .catch(err => console.error('Error:', err));
                });
            });
        });
    </script>

    <script src="js/bootstrap.bundle.min.js"></script>
</div>
