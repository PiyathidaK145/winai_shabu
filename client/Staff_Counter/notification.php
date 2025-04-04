<?php
date_default_timezone_set("Asia/Bangkok");
include dirname(__FILE__) . '/include/header.php';
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>Notification</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: 'Prompt', sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 10px;
            border-bottom: 1px solid #ccc;
            text-align: left;
        }

        th.sortable {
            cursor: pointer;
        }

        .btn-check {
            padding: 5px 10px;
            border: none;
            background-color: #28a745;
            color: white;
            border-radius: 5px;
        }

        .btn-check.read {
            background-color: #6c757d;
            cursor: default;
        }

        .notification-read {
            background-color: #f8f9fa;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <main class="main-wrapper col-md-9 ms-sm-auto py-4 col-lg-9 px-md-4 border-start">
            <h2 class="mb-4">การแจ้งเตือน</h2>
            <table>
                <thead>
                    <tr>
                        <th>ลำดับ</th>
                        <th>ข้อความ</th>
                        <th class="sortable" onclick="toggleSort()">เวลา <i id="sortIcon" class="fa fa-arrow-down"></i></th>
                        <th>Check
                            <select id="statusFilter" onchange="fetchNotifications()" style="margin-left: 5px; padding: 4px; font-size: 13px;">
                                <option value="all">ทั้งหมด</option>
                                <option value="read">อ่านแล้ว</option>
                                <option value="unread">ยังไม่ได้อ่าน</option>
                            </select>
                        </th>
                    </tr>
                </thead>
                <tbody id="notificationBody"></tbody>
            </table>
        </main>
    </div>

    <script>
        let sortAsc = false;

        function toggleSort() {
            sortAsc = !sortAsc;
            document.getElementById('sortIcon').className = sortAsc ? 'fa fa-arrow-up' : 'fa fa-arrow-down';
            fetchNotifications();
        }

        function markAsRead(id, checkbox) {
            if (checkbox.checked) {
                fetch('mark_read.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        id: id,
                        employee_id: 2
                    })
                }).then(() => fetchNotifications());
            }
        }

        function fetchNotifications() {
            const filter = document.getElementById('statusFilter').value;

            fetch('get_notifications.php?sort=' + (sortAsc ? 'asc' : 'desc'))
                .then(res => res.json())
                .then(data => {
                    const tbody = document.getElementById('notificationBody');
                    tbody.innerHTML = '';
                    let filteredData = data;

                    if (filter !== 'all') {
                        filteredData = data.filter(n => n.status === filter);
                    }

                    filteredData.forEach((n, index) => {
                        const row = document.createElement('tr');
                        if (n.status === 'read') row.classList.add('notification-read');

                        const checked = n.status === 'read' ? 'checked disabled' : '';

                        row.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${n.message}</td>
                    <td>${n.time}</td>
                    <td>
                        <input type="checkbox" onchange="markAsRead(${n.id}, this)" ${checked}>
                    </td>
                `;
                        tbody.appendChild(row);
                    });
                });
        }

        fetchNotifications();
        setInterval(fetchNotifications, 5000); // auto-refresh every 5s
    </script>
</body>
<?php include dirname(__FILE__) . '/include/footer.php'; ?>

</html>