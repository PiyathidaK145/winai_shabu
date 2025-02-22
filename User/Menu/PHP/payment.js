// ฟังก์ชันดึง reservation_id จาก URL
function getReservationIdFromURL() {
  const reservationId = getReservationIdFromURL();
  console.log("reservation_id:", reservationId); // ดูค่าใน console
  
}
const data = {
  reservation_id: reservationId,  // เพิ่ม reservation_id
  table_id: tableID,
  package_id: packageID,
  promotion_id: promotionID,
  package_price: packagePrice,
  payment_method: paymentMethod
};
console.log("กำลังส่งข้อมูลการชำระเงิน...", data);

// ฟังก์ชันแสดงการเลือกชำระเงิน
function showPaymentOption() {
  const paymentMethod = document.getElementById("payment-method").value;
  const qrCodeDiv = document.getElementById("qr-code");

  console.log("วิธีชำระเงินที่เลือก:", paymentMethod); // Debug ค่า

  // แสดงหรือซ่อน QR code
  qrCodeDiv.style.display = paymentMethod === "qr" ? "block" : "none";
}

// ฟังก์ชันยืนยันการชำระเงิน
function confirmPayment() {
  const reservationId = getReservationIdFromURL(); // ดึงค่า reservation_id
  if (!reservationId) {
      alert("ไม่พบรหัสการจอง (reservation_id)");
      return;
  }

  const tableID = document.getElementById("table-number").innerText;
  const packageID = document.getElementById("package-id").value;
  const promotionID = document.getElementById("promotion-id").value;
  const packagePrice = document.getElementById("package-price").value;
  const paymentMethod = document.getElementById("payment-method").value;

  if (paymentMethod === "เลือกวิธีชำระเงิน") {
      alert("กรุณาเลือกวิธีชำระเงิน");
      return;
  }

  const data = {
      reservation_id: reservationId,  // เพิ่ม reservation_id
      table_id: tableID,
      package_id: packageID,
      promotion_id: promotionID,
      package_price: packagePrice,
      payment_method: paymentMethod
  };

  console.log("กำลังส่งข้อมูลการชำระเงิน...", data);

  fetch("process_payment.php", {
      method: "POST",
      headers: {
          "Content-Type": "application/json"
      },
      body: JSON.stringify(data)
  })
  .then(response => response.json())
  .then(result => {
      if (result.success) {
          alert("ชำระเงินสำเร็จ!");
          window.location.href = "success_page.php"; // ไปที่หน้าหลังชำระเงินเสร็จ
      } else {
          alert("เกิดข้อผิดพลาด: " + result.message);
      }
  })
  .catch(error => console.error("Error:", error));
}
