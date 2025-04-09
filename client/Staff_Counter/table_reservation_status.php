<?php
date_default_timezone_set("Asia/Bangkok");
include '../../config/connect_db.php';

$today = date("Y-m-d");

$sql_table_use = "SELECT 
    g.created_at,

    -- Walk-in
    w.walkin_id,
    c_walkin.first_name AS walkin_first_name,
    c_walkin.last_name AS walkin_last_name,
    a_walkin.table_id AS walkin_table_id,
    a_walkin.status AS walkin_status,
    t_walkin.time AS walkin_time,
    t_walkin.time_id AS walkin_time_id,

    -- Reservation
    r.reservation_id,
    c_re.first_name AS reservation_first_name,
    c_re.last_name AS reservation_last_name,
    a_re.table_id AS reservation_table_id,
    a_re.status AS reservation_status,
    t_reserve.time AS reservation_time,
    t_reserve.time_id AS reservation_time_id,

    -- แพ็คเกจและโปรโมชัน
    p.package_name,
    promo.promotions_name,

    -- จำนวนคน
    w.number_of_guest AS walkin_guest,
    r.number_of_guest AS reservation_guest,

    -- โปรโมชัน item
    pi.discount_type,
    pi.discount_value,
    
    -- ยอดชำระ
    g.total_amount

FROM getting_table g

-- Walk-in
LEFT JOIN walkin w ON g.walkin_id = w.walkin_id
LEFT JOIN customer c_walkin ON w.customer_id = c_walkin.customer_id
LEFT JOIN table_availability a_walkin ON w.availability_id = a_walkin.availability_id
LEFT JOIN time_reserversion t_walkin ON a_walkin.time_id = t_walkin.time_id

-- Reservation
LEFT JOIN reservation r ON g.reservation_id = r.reservation_id
LEFT JOIN customer c_re ON r.customer_id = c_re.customer_id
LEFT JOIN table_availability a_re ON r.availability_id = a_re.availability_id
LEFT JOIN time_reserversion t_reserve ON a_re.time_id = t_reserve.time_id

-- แพ็คเกจ/โปรโมชัน
LEFT JOIN package p ON g.package_id = p.package_id
LEFT JOIN promotion promo ON g.promotion_id = promo.promotion_id
LEFT JOIN promotion_item pi ON promo.promotion_id = pi.promotion_id

WHERE DATE(g.created_at) = '$today' AND (a_walkin.status = 'busy' OR a_re.status = 'busy')
";


$result_table_use = mysqli_query($conn, query: $sql_table_use);

// ฟังก์ชันคำนวณเวลาคงเหลือ

function getTimeSlotRange($time_id)
{
    $map = [
        1001 => '16-18',
        1002 => '18-20',
        1003 => '20-22',
        1004 => '22-00',
        1005 => '00-02'
    ];
    return $map[$time_id] ?? 'ไม่ทราบช่วงเวลา';
}


// ฟังก์ชันคำนวณเวลาคงเหลือ
function getRemainingTimeToSlotEnd($created_at, $time_id)
{
    $slot_end_map = [
        1001 => '18:00:00',
        1002 => '20:00:00',
        1003 => '22:00:00',
        1004 => '00:00:00',
        1005 => '02:00:00'
    ];

    // ถ้า time_id ไม่อยู่ใน map
    if (!isset($slot_end_map[$time_id])) {
        return "ไม่ทราบช่วงเวลา";
    }

    // แปลง created_at เป็นวันที่
    $date = date('Y-m-d', strtotime($created_at));
    $slot_end_time = $slot_end_map[$time_id];

    // กรณีพิเศษ: ถ้าช่วงเวลาสิ้นสุดข้ามวัน (00:00 หรือ 02:00)
    if (in_array($time_id, [1004, 1005])) {
        $date = date('Y-m-d', strtotime($date . ' +1 day'));
    }

    // รวมวัน + เวลาสิ้นสุดของช่วง
    $end_timestamp = strtotime($date . ' ' . $slot_end_time);
    $remaining = $end_timestamp - time();

    if ($remaining <= 0) {
        return "หมดเวลา";
    }

    $hours = floor($remaining / 3600);
    $minutes = floor(($remaining % 3600) / 60);
    $seconds = $remaining % 60;

    return sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
}

?>

<table id="tableUse" class="table_use table-bordered ">
    <thead class="table-warning">
        <tr>
            <th onclick="sortTableByNumber()" style="cursor:pointer;">
                หมายเลขโต๊ะ <i id="sortIcon" class="fa-solid fa-arrow-down"></i>
            </th>
            <th>ชื่อ</th>
            <th>นามสกุล</th>
            <th>แพ็คเกจ</th>
            <th>โปรโมชัน</th>
            <th>เวลาเริ่มต้น</th>
            <th onclick="sortRemainingTime()" style="cursor: pointer;">
                เวลาคงเหลือ <i id="sortTimeIcon" class="fa-solid fa-arrow-down"></i>
            </th>
            <th>สถานะ
                <select id="statusFilter" class="form-select form-select-sm mt-1" onchange="filterStatus()">
                    <option value="all">ทั้งหมด</option>
                    <option value="กำลังใช้งานอยู่">กำลังใช้งานอยู่</option>
                    <option value="เหลือเวลา 30 นาที">เหลือเวลา 30 นาที</option>
                    <option value="เหลือเวลา 15 นาที">เหลือเวลา 15 นาที</option>
                    <option value="หมดเวลา">หมดเวลา</option>
                </select>
            </th>
            <th>ยอดชำระ</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = mysqli_fetch_assoc($result_table_use)) : ?>
            <?php
            $isWalkin = !empty($row['walkin_id']);
            $isReservation = !empty($row['reservation_id']);
            ?>
            <tr>
                <td><?= $isWalkin ? $row['walkin_table_id'] : $row['reservation_table_id'] ?></td>
                <td><?= $isWalkin ? $row['walkin_first_name'] : $row['reservation_first_name'] ?></td>
                <td><?= $isWalkin ? $row['walkin_last_name'] : $row['reservation_last_name'] ?></td>
                <td><?= $row['package_name'] ?></td>
                <td><?= $row['promotions_name'] ?? '-' ?></td>
                <td><?= date('H:i:s', strtotime($row['created_at'])) ?></td>
                <td class="remaining-time"
                    data-created="<?= $row['created_at'] ?>"
                    data-timeid="<?= $isWalkin ? $row['walkin_time_id'] : $row['reservation_time_id'] ?>">
                    กำลังโหลด...
                </td>
                <td class="status-time"
                    data-created="<?= $row['created_at'] ?>"
                    data-timeid="<?= $isWalkin ? $row['walkin_time_id'] : $row['reservation_time_id'] ?>">
                    <span class="badge">กำลังโหลด...</span>
                </td>
                <td>
                    <?php
                    $total_amount = $row['total_amount'];
                    $number_of_guest = $isWalkin ? $row['walkin_guest'] : $row['reservation_guest'];
                    $discount_type = $row['discount_type'];
                    $discount_value = $row['discount_value'];

                    $subtotal = $total_amount * $number_of_guest;
                    $discount = 0;

                    if ($discount_type === 'percentage') {
                        $discount = $subtotal * $discount_value;
                    } elseif ($discount_type === 'fixed_amount') {
                        $discount = $discount_value;
                    } elseif ($discount_type === 'count_number') {
                        $free_count = floor($number_of_guest / ($discount_value + 1));
                        $discount = $free_count * $total_amount;
                    } else {
                        $discount = 0;
                    }


                    $after_discount = $subtotal - $discount;
                    $vat = $after_discount * 0.07;
                    $final_total = $after_discount + $vat;

                    echo number_format($final_total, 2) . ' บาท';
                    ?>
                </td>

            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<script>
    function getRemainingTime(createdAt, timeId) {
        const slotEndMap = {
            1001: "18:00:00",
            1002: "20:00:00",
            1003: "22:00:00",
            1004: "00:00:00",
            1005: "02:00:00"
        };

        if (!slotEndMap[timeId]) return "ไม่ทราบช่วงเวลา";

        let createdDate = new Date(createdAt);
        let dateStr = createdDate.toISOString().split("T")[0]; // yyyy-mm-dd

        if (timeId == 1004 || timeId == 1005) {
            createdDate.setDate(createdDate.getDate() + 1);
            dateStr = createdDate.toISOString().split("T")[0];
        }

        let endDateTime = new Date(dateStr + "T" + slotEndMap[timeId]);
        let now = new Date();
        let diff = endDateTime - now;

        if (diff <= 0) return "หมดเวลา";

        let hours = Math.floor(diff / 3600000);
        let minutes = Math.floor((diff % 3600000) / 60000);
        let seconds = Math.floor((diff % 60000) / 1000);

        return `${hours}:${minutes}:${seconds}`;
    }

    function updateRemainingTimes() {
        document.querySelectorAll('.remaining-time').forEach(el => {
            const createdAt = el.getAttribute('data-created');
            const timeId = el.getAttribute('data-timeid');

            const slotEndMap = {
                1001: "18:00:00",
                1002: "20:00:00",
                1003: "22:00:00",
                1004: "00:00:00",
                1005: "02:00:00"
            };

            if (!slotEndMap[timeId]) {
                el.textContent = "ไม่ทราบช่วงเวลา";
                el.setAttribute("data-remaining", -1);
                return;
            }

            let createdDate = new Date(createdAt);
            let dateStr = createdDate.toISOString().split("T")[0];

            if (timeId == 1004 || timeId == 1005) {
                createdDate.setDate(createdDate.getDate() + 1);
                dateStr = createdDate.toISOString().split("T")[0];
            }

            let endDateTime = new Date(dateStr + "T" + slotEndMap[timeId]);
            let now = new Date();
            let diff = Math.floor((endDateTime - now) / 1000); // ← คำนวณเวลาคงเหลือเป็นวินาที

            if (diff <= 0) {
                el.textContent = "หมดเวลา";
                el.setAttribute("data-remaining", 0);
            } else {
                let hours = Math.floor(diff / 3600);
                let minutes = Math.floor((diff % 3600) / 60);
                let seconds = diff % 60;
                el.textContent = `${hours}:${minutes}:${seconds}`;
                el.setAttribute("data-remaining", diff);
            }
        });
    }

    setInterval(() => {
        updateRemainingTimes();
        updateStatuses();
    }, 1000);

    // ตอนโหลดครั้งแรก
    updateRemainingTimes();
    updateStatuses();

    let sortAsc = true;

    function sortTableByNumber() {
        const table = document.getElementById("tableUse");
        const tbody = table.querySelector("tbody");
        const rows = Array.from(tbody.querySelectorAll("tr"));

        rows.sort((a, b) => {
            const numA = parseInt(a.cells[0].innerText.trim());
            const numB = parseInt(b.cells[0].innerText.trim());
            return sortAsc ? numA - numB : numB - numA;
        });

        tbody.innerHTML = "";
        rows.forEach(row => tbody.appendChild(row));

        // แก้ตรงนี้: เปลี่ยน icon
        const icon = document.getElementById("sortIcon");
        icon.classList.remove("fa-arrow-down", "fa-arrow-up"); // ลบออกก่อน
        icon.classList.add(sortAsc ? "fa-arrow-up" : "fa-arrow-down"); // เพิ่มตามทิศทาง

        sortAsc = !sortAsc;
    }

    let sortTimeAsc = true;

    function sortRemainingTime() {
        const table = document.getElementById("tableUse");
        const tbody = table.querySelector("tbody");
        const rows = Array.from(tbody.querySelectorAll("tr"));

        rows.sort((a, b) => {
            const timeA = a.querySelector('.remaining-time').getAttribute('data-remaining');
            const timeB = b.querySelector('.remaining-time').getAttribute('data-remaining');

            const secA = parseInt(timeA);
            const secB = parseInt(timeB);

            return sortTimeAsc ? secA - secB : secB - secA;
        });

        tbody.innerHTML = "";
        rows.forEach(row => tbody.appendChild(row));

        const icon = document.getElementById("sortTimeIcon");
        icon.classList.remove("fa-arrow-down", "fa-arrow-up");
        icon.classList.add(sortTimeAsc ? "fa-arrow-up" : "fa-arrow-down");

        sortTimeAsc = !sortTimeAsc;
    }

    function filterStatus() {
        const selected = document.getElementById("statusFilter").value;
        const rows = document.querySelectorAll("#tableUse tbody tr");

        rows.forEach(row => {
            const statusCell = row.querySelector(".status-time span");
            const statusText = statusCell ? statusCell.textContent.trim() : "";

            if (selected === "all" || statusText === selected) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    }

    function updateStatuses() {
        document.querySelectorAll('.status-time').forEach(el => {
            const createdAt = el.getAttribute('data-created');
            const timeId = el.getAttribute('data-timeid');

            const slotEndMap = {
                1001: "18:00:00",
                1002: "20:00:00",
                1003: "22:00:00",
                1004: "00:00:00",
                1005: "02:00:00"
            };

            if (!slotEndMap[timeId]) {
                el.innerHTML = '<span class="badge bg-secondary">ไม่ทราบ</span>';
                return;
            }

            let createdDate = new Date(createdAt);
            let dateStr = createdDate.toISOString().split("T")[0];

            if (timeId == "1004" || timeId == "1005") {
                createdDate.setDate(createdDate.getDate() + 1);
                dateStr = createdDate.toISOString().split("T")[0];
            }

            let endDateTime = new Date(dateStr + "T" + slotEndMap[timeId]);
            let now = new Date();
            let diffSec = Math.floor((endDateTime - now) / 1000);

            let badgeClass = "bg-success";
            let label = "กำลังใช้งานอยู่";

            if (diffSec <= 0) {
                badgeClass = "bg-dark";
                label = "หมดเวลา";
            } else if (diffSec <= 900) {
                badgeClass = "bg-danger";
                label = "เหลือเวลา 15 นาที";
            } else if (diffSec <= 1800) {
                badgeClass = "bg-warning text-dark";
                label = "เหลือเวลา 30 นาที";
            }

            el.innerHTML = `<span class="badge ${badgeClass}">${label}</span>`;
        });

        filterStatus();
    }
</script>