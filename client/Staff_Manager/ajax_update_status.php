<?php
include dirname(__FILE__) . '/../../config/connect_db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['promotion_item_id'])) {
    $promotion_item_id = $_POST['promotion_item_id'];

    $stmt = $conn->prepare("UPDATE promotion_item SET status = 'expired' WHERE promotion_item_id = ? AND status = 'active'");
    $stmt->execute([$promotion_item_id]);

    echo "success";
}
?>