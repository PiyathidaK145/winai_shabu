<?php
date_default_timezone_set("Asia/Bangkok");
include dirname(__FILE__) . '/include/header.php';
include dirname(__FILE__) . '/../../config/connect_db.php';
include dirname(__FILE__) . '/model/modal_reserved.php';
include dirname(__FILE__) . '/model/modal_available.php';

$today = date("Y-m-d");
$current_time = date("H:i:s");

function getTimeRange($selected_time, $today)
{
    switch ($selected_time) {
        case '16-18':
            $start = "$today 00:00:00";
            $end   = "$today 23:59:59";
            break;
        case '18-20':
            $start = "$today 00:00:00";
            $end   = "$today 23:59:59";
            break;
        case '20-22':
            $start = "$today 00:00:00";
            $end   = "$today 23:59:59";
            break;
        case '22-00':
            $start = "$today 00:00:00";
            $end   = "$today 23:59:59";
            break;
        case '00-02':
            //ตรงนี้ยังติดตรง logic
            $yesterday = date('Y-m-d', strtotime($today . " -1 day"));
            $tomorrow = date("Y-m-d", strtotime($today . " +1 day"));
            $start = "$today 00:00:00";
            $end   = "$tomorrow 01:59:59";
            break;
        default:
            $start = "$today 00:00:00";
            $end   = "$today 23:59:59";
    }
    return [$start, $end];
}

if (isset($_GET['time'])) {
    $selected_time = $_GET['time'];
} else {
    $current_hour = (int) date("H");
    if ($current_hour >= 16 && $current_hour < 18) {
        $selected_time = "16-18";
    } elseif ($current_hour >= 18 && $current_hour < 20) {
        $selected_time = "18-20";
    } elseif ($current_hour >= 20 && $current_hour < 22) {
        $selected_time = "20-22";
    } elseif ($current_hour >= 22 && $current_hour < 0) {
        $selected_time = "22-00";
    } elseif ($current_hour >= 0 && $current_hour < 2) {
        $selected_time = "00-02";
    } else {
        $selected_time = "16-18";
    }
}

$time_map = [
    '16-18' => 1001,
    '18-20' => 1002,
    '20-22' => 1003,
    '22-00' => 1004,
    '00-02' => 1005
];
$time_id = $time_map[$selected_time] ?? 1001;

// กำหนดสถานะเริ่มต้นให้โต๊ะ 1-20 = available
$tables = [];
for ($i = 1; $i <= 20; $i++) {
    $tables[$i] = ['status' => 'available'];
}
$reserved_count = 0;
$occupied_count = 0;

// ดึงโต๊ะที่ "กำลังกินอยู่"
$sql1 = "
    SELECT a.table_id, p.payment_timestamp
    FROM getting_table g
    LEFT JOIN reservation r ON g.reservation_id = r.reservation_id
    LEFT JOIN walkin w ON g.walkin_id = w.walkin_id
    LEFT JOIN table_availability a 
        ON a.availability_id = COALESCE(r.availability_id, w.availability_id)
    LEFT JOIN payment p ON p.getting_table_id = g.getting_table_id
    WHERE DATE(g.created_at) = '$today'
";
$result1 = mysqli_query($conn, $sql1);
if ($result1 && mysqli_num_rows($result1) > 0) {
    while ($row = mysqli_fetch_assoc($result1)) {
        $table_id = $row['table_id'];
        $payment_done = !empty($row['payment_timestamp']);
        if (!$payment_done) {
            $tables[$table_id]['status'] = 'occupied';
            $occupied_count++; // แดงถ้ายังกินอยู่
        } else {
            $tables[$table_id]['status'] = 'available'; // เขียวถ้าจ่ายเงินแล้ว
        }
    }
}

list($start_datetime, $end_datetime) = getTimeRange($selected_time, $today);
// ดึงสถานะจาก table_availability สำหรับช่วงเวลาที่เลือก
$sql2 = "
    SELECT 
        a.table_id, 
        a.status,
        r.reservation_id,
        w.walkin_id
    FROM table_availability a
    INNER JOIN time_reserversion t ON a.time_id = t.time_id
    LEFT JOIN reservation r ON a.availability_id = r.availability_id
    LEFT JOIN walkin w ON a.availability_id = w.availability_id
    WHERE a.time_id = $time_id 
      AND a.status = 'Busy'
      AND (
          (
              r.reservation_id IS NOT NULL 
              AND r.status = 'Confirm'
              AND a.last_update BETWEEN '$start_datetime' AND '$end_datetime'
          )
          OR
          (
              w.walkin_id IS NOT NULL
              AND a.last_update BETWEEN '$start_datetime' AND '$end_datetime'
          )
      )
";

// Debugging: Output the SQL query for verification
//echo "<pre>DEBUG: SQL Query = $sql2</pre>";

$result2 = mysqli_query($conn, $sql2);
if ($result2 && mysqli_num_rows($result2) > 0) {
    while ($row = mysqli_fetch_assoc($result2)) {
        $table_id = $row['table_id'];
        if (
            (!isset($tables[$table_id]['status']) || $tables[$table_id]['status'] !== 'occupied') &&
            (!isset($tables[$table_id]['counted']) || $tables[$table_id]['counted'] !== true)
        ) {
            $tables[$table_id]['status'] = 'reserved';
            $tables[$table_id]['counted'] = true; // ✅ ป้องกันนับซ้ำ
            $reserved_count++;
            // Debugging: Output reserved table details
            //echo "$reserved_count DEBUG: Reserved Table ID = $table_id<br>";

            // 🔍 กำหนด type ให้แยกว่าเป็น walkin หรือ reservation
            if (!empty($row['reservation_id'])) {
                $tables[$table_id]['type'] = 'reservation';
            } elseif (!empty($row['walkin_id'])) {
                $tables[$table_id]['type'] = 'walkin';
            } else {
                $tables[$table_id]['type'] = 'unknown';
            }
        }
    }
}


// นับโต๊ะว่าง
$available_count = 0;
foreach ($tables as $t) {
    if ($t['status'] === 'available') $available_count++;
}


?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>หน้าหลัก</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/table_status.css" rel="stylesheet">
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const labels = document.querySelectorAll(".filter-label");

            labels.forEach(label => {
                label.addEventListener("click", function() {
                    document.querySelectorAll(".filter-label").forEach(l => l.classList.remove("selected"));
                    label.classList.add("selected");
                    const input = label.querySelector("input");
                    input.checked = true;
                    const form = document.createElement("form");
                    form.method = "GET";
                    form.action = "";
                    const clone = input.cloneNode();
                    form.appendChild(clone);
                    document.body.appendChild(form);
                    form.submit();
                });
            });
        });
    </script>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <main class="main-wrapper col-md-9 ms-sm-auto py-4 col-lg-9 px-md-4 border-start">
                <form id="filter-form">
                    <div class="filter-container">
                        <label class="filter-label <?php echo (isset($_GET['time']) && $_GET['time'] === "16-18") ? 'selected' : ''; ?>">
                            <input type="radio" name="time" value="16-18" hidden <?php echo (isset($_GET['time']) && $_GET['time'] === "16-18") ? 'checked' : ''; ?>>
                            16:00 - 18:00
                        </label>
                        <label class="filter-label <?php echo (isset($_GET['time']) && $_GET['time'] === "18-20") ? 'selected' : ''; ?>">
                            <input type="radio" name="time" value="18-20" hidden <?php echo (isset($_GET['time']) && $_GET['time'] === "18-20") ? 'checked' : ''; ?>>
                            18:00 - 20:00
                        </label>

                        <label class="filter-label <?php echo (isset($_GET['time']) && $_GET['time'] === "20-22") ? 'selected' : ''; ?>">
                            <input type="radio" name="time" value="20-22" hidden <?php echo (isset($_GET['time']) && $_GET['time'] === "20-22") ? 'checked' : ''; ?>>
                            20:00 - 22:00
                        </label>

                        <label class="filter-label <?php echo (isset($_GET['time']) && $_GET['time'] === "22-00") ? 'selected' : ''; ?>">
                            <input type="radio" name="time" value="22-00" hidden <?php echo (isset($_GET['time']) && $_GET['time'] === "22-00") ? 'checked' : ''; ?>>
                            22:00 - 00:00
                        </label>
                        <label class="filter-label <?php echo (isset($_GET['time']) && $_GET['time'] === "00-02") ? 'selected' : ''; ?>">
                            <input type="radio" name="time" value="00-02" hidden <?php echo (isset($_GET['time']) && $_GET['time'] === "00-02") ? 'checked' : ''; ?>>
                            00:00 - 02:00
                        </label>
                    </div>
                </form>

                <!-- แสดงผลสรุป -->
                <div class="container my-4">
                    <div class="row g-3 justify-content-center">

                        <!-- วันที่และเวลา -->
                        <div class="col-12 text-center mb-2">
                            <h5 class="fw-bold text-secondary">
                                📅 <span class="me-2">วันที่: <?= $today ?></span>
                                🕒 เวลา: <span id="current-time"></span>
                                | <?= $selected_time ?>
                            </h5>
                        </div>

                        <!-- กำลังกินอยู่ -->
                        <div class="col-md-3 col-sm-6">
                            <div class="p-3 rounded-3 text-center" style="border: 1px solid #ccc;">
                                <div class="fw-bold" style="color: #e53935;">🟥 กำลังใช้งาน</div>
                                <div class="display-6 fw-bold text-dark"><?= $occupied_count ?></div>
                                <div class="text-muted small">โต๊ะ</div>
                            </div>
                        </div>

                        <!-- จองแล้ว -->
                        <div class="col-md-3 col-sm-6">
                            <div class="p-3 rounded-3 text-center" style="border: 1px solid #ccc;">
                                <div class="fw-bold" style="color: #ff9800;">🟧 จองแล้ว</div>
                                <div class="display-6 fw-bold text-dark"><?= $reserved_count ?></div>
                                <div class="text-muted small">โต๊ะ</div>
                            </div>
                        </div>

                        <!-- โต๊ะว่าง -->
                        <div class="col-md-3 col-sm-6">
                            <div class="p-3 rounded-3 text-center" style="border: 1px solid #ccc;">
                                <div class="fw-bold" style="color: #4caf50;">🟩 โต๊ะว่าง</div>
                                <div class="display-6 fw-bold text-dark"><?= $available_count ?></div>
                                <div class="text-muted small">โต๊ะ</div>
                            </div>
                        </div>
                    </div>
                </div>


                <section id="map">


                    <section id="rectangle-under-map">
                        <div class="rectangle-box">
                            <?php
                            // จำนวนโต๊ะที่มีทั้งหมด (สมมติว่า 20 โต๊ะ)
                            for ($i = 1; $i <= 20; $i++) {
                                $status_class = 'table-available'; // default = เขียว

                                switch ($tables[$i]['status']) {
                                    case 'available':
                                        $status_class = 'table-available'; // เขียว
                                        $onclick = "data-bs-toggle='modal' data-bs-target='#walkinModal' onclick='openWalkinModal($i, $i, \"$selected_time\", $time_id)'";

                                        break;
                                    case 'reserved':
                                        $status_class = 'table-reserved'; // ส้ม
                                        if (isset($tables[$i]['type']) && $tables[$i]['type'] === 'reservation') {
                                            $onclick = "onclick='openReservedModal($i, \"$selected_time\")'";
                                        } elseif (isset($tables[$i]['type']) && $tables[$i]['type'] === 'walkin') {
                                            $onclick = "onclick='openReservedModal($i, \"$selected_time\")'";
                                        } else {
                                            $onclick = '';
                                        }
                                        break;
                                    case 'occupied':
                                        $status_class = 'table-occupied'; // แดง
                                        $onclick = '';
                                        break;
                                }

                                echo "<div class=\"table table$i $status_class\" id=\"table-$i\" $onclick>$i</div>";
                            }
                            ?>
                            <div class="rectangle">ครัว</div>
                            <div class="rectangle1">ครัว</div>
                            <div class="rectangle2">แคชเชียร์</div>
                            <div class="rectangle3">ประตู</div>

                    </section>
                    <form id="walkinRedirectForm" action="show_walk_in.php" method="POST" style="display: none;">
                        <input type="hidden" name="walkin_id" id="formWalkinId">
                        <input type="hidden" name="first_name" id="formFirstName">
                        <input type="hidden" name="last_name" id="formLastName">
                        <input type="hidden" name="table_number" id="formTableNumber">
                        <input type="hidden" name="table_id" id="formTableId">
                        <input type="hidden" name="time_slot" id="formTimeSlot">
                        <input type="hidden" name="number_of_guest" id="formGuests">
                    </form>

                    <form id="reservationRedirectForm" action="show_reservation.php" method="POST" style="display: none;">
                        <input type="hidden" name="reservation_id" id="formReservationId">
                        <input type="hidden" name="table_id" id="formReservationTableId">
                        <input type="hidden" name="time_slot" id="formReservationTimeSlot">
                        <input type="hidden" name="first_name" id="formReservationFirstName">
                        <input type="hidden" name="last_name" id="formReservationLastName">
                        <input type="hidden" name="number_of_guest" id="formReservationGuests">
                    </form>
                </section>

                <div class="mt-4">
                    <h4 class="text-start my-6">
                        <div class="fw-bold">🟥 สถานะการใช้งานโต๊ะ</div>
                    </h4>
                    <?php include 'table_reservation_status.php'; ?>
                </div>


            </main>
        </div>
    </div>
    <script>
        // ฟังก์ชันเปิด modal สำหรับโต๊ะที่จองแล้ว
        function openReservedModal(tableId, timeSlot) {
            document.getElementById('reservedTableNumber').textContent = tableId;
            document.getElementById('reservedTimeSlot').textContent = timeSlot;

            console.log(`Fetching: get_reserved_data.php?table_id=${tableId}&time=${timeSlot}`); // Debug
            fetch(`get_reserved_data.php?table_id=${tableId}&time=${timeSlot}`) //debug
                .then(response => response.json())
                .then(data => {
                    console.log("✅ Reserved Data", data); // Debug
                    if (data.type === 'walkin') {
                        document.getElementById('walkinCodeDisplay').innerText = `รหัส - ${data.walkin_id}`;
                        document.getElementById('walkinTableNumber').innerText = tableId;
                        document.getElementById('walkinTimeSlot').innerText = timeSlot;
                        document.getElementById('walkinFirstName').innerText = data.first_name;
                        document.getElementById('walkinLastName').innerText = data.last_name;
                        document.getElementById('walkinGuests').innerText = data.number_of_guest;

                        const confirmInput = document.getElementById('confirmTableId_walkin');
                        if (confirmInput) confirmInput.value = tableId;

                        new bootstrap.Modal(document.getElementById('walkinReservedModal')).show();
                    } else if (data.type === 'reservation') {
                        document.getElementById('reservationIdHidden').value = data.reservation_id;
                        document.getElementById('reservedTableNumber').innerText = tableId;
                        document.getElementById('reservedTimeSlot').innerText = timeSlot;
                        document.getElementById('reservedFirstName').innerText = data.first_name;
                        document.getElementById('reservedLastName').innerText = data.last_name;
                        document.getElementById('reservedGuests').innerText = data.number_of_guest;
                        document.getElementById('confirmTableId').value = tableId;

                        new bootstrap.Modal(document.getElementById('reservedModal')).show();
                    }
                })
                .catch(error => {
                    console.error("ไม่สามารถโหลดข้อมูลการจองได้", error);
                    alert("ไม่สามารถโหลดข้อมูลการจองได้");
                });

            document.getElementById('verifyWalkinBtn').addEventListener('click', function() {
                const walkinId = document.getElementById('walkinCodeDisplay').innerText.replace('รหัส - ', '');
                const firstName = document.getElementById('walkinFirstName').innerText;
                const lastName = document.getElementById('walkinLastName').innerText;
                const tableNumber = document.getElementById('walkinTableNumber').innerText;
                const timeSlot = document.getElementById('walkinTimeSlot').innerText;
                const guests = document.getElementById('walkinGuests').innerText;

                // ✅ เพิ่มฟิลด์ table_id
                document.getElementById('formTableId').value = tableId;

                // ✅ ใส่ค่าอื่น ๆ ตามเดิม
                document.getElementById('formWalkinId').value = walkinId;
                document.getElementById('formFirstName').value = firstName;
                document.getElementById('formLastName').value = lastName;
                document.getElementById('formTableNumber').value = tableNumber;
                document.getElementById('formTimeSlot').value = timeSlot;
                document.getElementById('formGuests').value = guests;

                // ตั้ง action
                document.getElementById('walkinRedirectForm').action = `show_walk_in.php?id=${walkinId}`;

                // ส่งฟอร์ม
                document.getElementById('walkinRedirectForm').submit();
            });

            document.getElementById('checkBookingBtn').addEventListener('click', function() {
                const reservationId = document.getElementById('reservationIdHidden').value;
                const tableId = document.getElementById('confirmTableId').value;
                const timeSlot = document.getElementById('reservedTimeSlot').innerText;
                const firstName = document.getElementById('reservedFirstName').innerText;
                const lastName = document.getElementById('reservedLastName').innerText;
                const guests = document.getElementById('reservedGuests').innerText;

                // ✅ ใส่ค่าลงในฟอร์ม
                document.getElementById('formReservationId').value = reservationId;
                document.getElementById('formReservationTableId').value = tableId;
                document.getElementById('formReservationTimeSlot').value = timeSlot;
                document.getElementById('formReservationFirstName').value = firstName;
                document.getElementById('formReservationLastName').value = lastName;
                document.getElementById('formReservationGuests').value = guests;

                // ✅ ตั้ง action พร้อม id
                document.getElementById('reservationRedirectForm').action = `show_reservation.php?id=${reservationId}`;

                // ✅ ส่งฟอร์ม
                document.getElementById('reservationRedirectForm').submit();
            });

        }
        // ฟังก์ชันเปิด modal สำหรับโต๊ะว่าง
        function openWalkinModal(tableId, tableNumber, timeSlot, timeId) {
            const selectedTime = "<?= $selected_time ?>";
            let bookingTime = "16.00";

            switch (selectedTime) {
                case "16-18":
                    bookingTime = "16.00";
                    break;
                case "18-20":
                    bookingTime = "18.00";
                    break;
                case "20-22":
                    bookingTime = "20.00";
                    break;
                case "22-00":
                    bookingTime = "22.00";
                    break;
                case "00-02":
                    bookingTime = "00.00";
                    break;
            }

            const walkinForm = document.getElementById('walkinForm');
            walkinForm.reset();

            document.getElementById('walkinTableNumber').innerText = tableNumber;
            document.getElementById('walkinTableId').value = tableId;
            document.getElementById('walkinTimeId').value = timeId;
            document.getElementById('bookingTimeInput').value = bookingTime;

            // ✅ แก้เป็น backticks ตรงนี้
            fetch(`get_table_capacity.php?table_id=${tableId}`)
                .then(response => response.text())
                .then(text => {
                    console.log("Raw Response:", text);
                    const data = JSON.parse(text);
                    const guestInput = document.querySelector('#walkinForm input[name="number_of_guest"]');
                    guestInput.max = data.capacity;
                    //guestInput.placeholder = `สูงสุด ${data.capacity} คน`;
                })
                .catch(error => {
                    alert("ไม่สามารถโหลดข้อมูลความจุโต๊ะได้");
                    console.error("❌ Error fetching capacity:", error);
                });

            const walkinModal = new bootstrap.Modal(document.getElementById('walkinModal'));
            walkinModal.show();

            document.getElementById('walkinModal').addEventListener('hidden.bs.modal', function() {
                document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                document.body.classList.remove('modal-open');
                document.body.style = '';
            });

            const verifyBtn = document.getElementById('verifyWalkinBtn');

            // ลบ Event เดิมออก ถ้ามี
            const newBtn = verifyBtn.cloneNode(true);
            verifyBtn.parentNode.replaceChild(newBtn, verifyBtn);

            // เพิ่ม Event ใหม่
            newBtn.addEventListener('click', function() {
                const walkinId = document.getElementById('walkinCodeDisplay').innerText.replace('รหัส - ', '');
                window.location.href = `show_walk_in.php?id=${walkinId}`;
            });
        }

        document.getElementById('verifyWalkinBtn').addEventListener('click', function() {
            const walkinId = document.getElementById('walkinCodeDisplay').innerText.replace('รหัส - ', '');
            const tableNumber = document.getElementById('walkinTableNumber').innerText;
            const timeSlot = document.getElementById('walkinTimeSlot').innerText;

            window.location.href = `show_walk_in.php?id=${walkinId}&table_number=${tableNumber}&time=${timeSlot}`;
        });

        document.getElementById('checkBookingBtn').addEventListener('click', function(e) {
            e.preventDefault();

            const reservationCode = document.getElementById('reservationCode').value.trim();

            if (!reservationCode) {
                alert("กรุณากรอกรหัสการจอง");
                return;
            }
            //debug
            fetch(`check_reservation_id.php?id=${reservationCode}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'found') {
                        // ✅ กำหนดค่าจาก response ลงใน hidden input
                        document.getElementById('formReservationId').value = data.reservation_id;
                        document.getElementById('formReservationTableId').value = data.table_id;
                        document.getElementById('formReservationTimeSlot').value = data.time_slot;
                        document.getElementById('formReservationFirstName').value = data.first_name;
                        document.getElementById('formReservationLastName').value = data.last_name;
                        document.getElementById('formReservationGuests').value = data.number_of_guest;

                        // ✅ ตั้ง action แล้วส่งฟอร์มไป show_reservation.php
                        const form = document.getElementById('reservationRedirectForm');
                        form.action = `show_reservation.php?id=${data.reservation_id}`;
                        form.submit();
                    } else {
                        alert("ไม่พบรหัสการจองนี้ในระบบ");
                    }
                })
                .catch(error => {
                    console.error('เกิดข้อผิดพลาด', error);
                    alert("เกิดข้อผิดพลาดในการตรวจสอบรหัส");
                });
        });

        function updateDateTime() {
            const now = new Date();

            const date = now.toISOString().split('T')[0];

            let hours = now.getHours().toString().padStart(2, "0");
            let minutes = now.getMinutes().toString().padStart(2, "0");
            let seconds = now.getSeconds().toString().padStart(2, "0");

            const timeString = `${hours}:${minutes}:${seconds}`;
            const fullDateTime = `${date} ${timeString}`;

            // อัปเดตเวลาใน span
            document.getElementById("current-time").textContent = timeString;
        }

        // อัปเดตทุกวินาที
        setInterval(updateDateTime, 1000);
        updateDateTime(); // เรียกทันทีเมื่อโหลด
        setInterval(() => {
            window.location.reload();
        }, 5000);
    </script>
</body>
<?php include dirname(__FILE__) . '/include/footer.php'; ?>