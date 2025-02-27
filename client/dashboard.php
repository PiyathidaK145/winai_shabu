<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }

        .container {
            margin-top: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ddd;
            text-align: center;
            padding: 8px;
        }

        th {
            background-color: #007bff;
            color: darkblue;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        .btn-action {
            margin: 0 5px;
        }

        .btn-add {
            background-color: #28a745;
            color: #fff;
            margin-bottom: 20px;
        }

        .filter-form {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center">Dashboard</h1>

        <!-- Filter Form -->
        <form method="GET" class="filter-form">
            <div class="row">
                <div class="col-md-3">
                    <input type="text" name="name_filter" class="form-control" placeholder="Filter by Name" value="<?= isset($_GET['name_filter']) ? htmlspecialchars($_GET['name_filter']) : '' ?>">
                </div>
                <div class="col-md-3">
                    <input type="text" name="phone_filter" class="form-control" placeholder="Filter by Phone" value="<?= isset($_GET['phone_filter']) ? htmlspecialchars($_GET['phone_filter']) : '' ?>">
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Search</button>
            <a href="Customer_List.php" class="btn btn-primary mt-3">Back to All Customers List</a>
        </form>

        <!-- Buttons -->
        <a href="add_customer.php" class="btn btn-add">Add New Member</a>
        
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Table</th>
                    <th>Guests</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Mock Data for customer members (replace this with database data)
                $customerList = [
                    ['id' => 1, 'Table' => '5', 'Guests' => '25', 'Status' => 500],
                    ['id' => 2, 'Table' => '3', 'Guests' => '10', 'Status' => 350],
                    ['id' => 3, 'Table' => '2', 'Guests' => '5', 'Status' => 700],
                    // Add more customer data as needed
                ];

                // Apply filters if set
                $filteredCustomerList = $customerList;

                if (isset($_GET['name_filter']) && $_GET['name_filter'] !== '') {
                    $filteredCustomerList = array_filter($filteredCustomerList, function($customer) {
                        return stripos($customer['name'], $_GET['name_filter']) !== false;
                    });
                }

                if (isset($_GET['phone_filter']) && $_GET['phone_filter'] !== '') {
                    $filteredCustomerList = array_filter($filteredCustomerList, function($customer) {
                        return stripos($customer['phone'], $_GET['phone_filter']) !== false;
                    });
                }

                // Display filtered customer data in table rows
                foreach ($filteredCustomerList as $customer) {
                    echo "<tr>";
                    echo "<td>{$customer['id']}</td>";
                    echo "<td>{$customer['name']}</td>";
                    echo "<td>{$customer['phone']}</td>";
                    echo "<td>{$customer['total_spend']}</td>";
                    echo "<td>{$customer['items']}</td>";
                    echo "<td>{$customer['hours']}</td>";
                    echo "<td>{$customer['guests']}</td>";
                    echo "<td>
                        <a href='edit_customer.php?id={$customer['id']}' class='btn btn-warning btn-sm btn-action'>Edit</a>
                        <a href='delete_customer.php?id={$customer['id']}' class='btn btn-danger btn-sm btn-action' onclick='return confirm(\"Are you sure you want to delete this record?\")'>Delete</a>
                    </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
