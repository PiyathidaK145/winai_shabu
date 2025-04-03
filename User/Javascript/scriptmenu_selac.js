// ฟังก์ชันที่ใช้เปลี่ยนหน้าไปยัง payment.php พร้อมส่ง reservation_id
function redirectToPayment(reservationId) {
    window.location.href = `../Payment/payment.php?reservation_id=${reservationId}`;
}
