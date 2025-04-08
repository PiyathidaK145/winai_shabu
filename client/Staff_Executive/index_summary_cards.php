<?php
include dirname(__FILE__) . '/../../config/connect_db.php';
?>

<!-- Link to custom CSS -->
<link rel="stylesheet" href="assets/css/summary_cards.css">

<div class="row mb-4">
    <?php
    function getCount($conn, $table) {
        $sql = "SELECT COUNT(*) AS count FROM `$table`";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        return $row['count'];
    }

    $cards = [
        ["title" => "จำนวนรายการวัตถุดิบ", "table" => "raw_material", "class" => "bg-primary"],
        ["title" => "จำนวนแพ็คเกจ", "table" => "package", "class" => "bg-success"],
        ["title" => "จำนวนหมวดหมู่", "table" => "category", "class" => "bg-warning"],
        ["title" => "จำนวนซัพพลายเออร์", "table" => "supplier", "class" => "bg-danger"],
        ["title" => "จำนวนคลังจัดเก็บ", "table" => "warehouse", "class" => "bg-info"],
    ];

    foreach ($cards as $card) {
        $count = getCount($conn, $card['table']);
        echo '
        <div class="col-md-2">
            <div class="card text-white ' . $card['class'] . ' summary-card mb-3">
                <div class="card-body text-center">
                    <h5 class="card-title">' . $card['title'] . '</h5>
                    <p class="card-text display-6">' . $count . '</p>
                </div>
            </div>
        </div>';
    }
    ?>
</div>

<!-- Optional JS -->
<script src="assets/js/summary_cards.js"></script>
