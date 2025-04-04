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
    t_walkin.time AS walkin_time,
    t_walkin.time_id AS walkin_time_id,

    -- Reservation
    r.reservation_id,
    c_re.first_name AS reservation_first_name,
    c_re.last_name AS reservation_last_name,
    a_re.table_id AS reservation_table_id,
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
    pi.discount_value

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

WHERE DATE(g.created_at) = '$today'
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

<table class="table table-bordered ">
    <thead class="table-warning">
        <tr>
            <th>หมายเลขโต๊ะ</th>
            <th>ชื่อ</th>
            <th>นามสกุล</th>
            <th>แพ็คเกจ</th>
            <th>โปรโมชัน</th>
            <th>เวลาเริ่มต้น</th>
            <th>เวลาคงเหลือ</th>
            <th>สถานะ</th>
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
                <td><?= date('H:i', strtotime($row['created_at'])) ?></td>
                <td class="remaining-time"
                    data-created="<?= $row['created_at'] ?>"
                    data-timeid="<?= $isWalkin ? $row['walkin_time_id'] : $row['reservation_time_id'] ?>">
                    กำลังโหลด...
                </td>
                <td>
                    <span class="badge bg-success">
                        <?= $isWalkin ? 'Walk-in' : 'Reservation' ?>
                    </span>
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
            el.textContent = getRemainingTime(createdAt, timeId);
        });
    }

    // เรียกฟังก์ชันทุก 1 วินาที
    setInterval(updateRemainingTimes, 1000);
    updateRemainingTimes(); // เรียกตอนโหลดครั้งแรก
</script>