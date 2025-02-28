// ฟังก์ชันนี้จะถูกเรียกเมื่อคลิกปุ่มปิด (×)
function closeReceipt() {
    window.location.href = "review.php?receipt_id=" + receipt_id; // ส่ง receipt_id ไปที่ review.php
}
