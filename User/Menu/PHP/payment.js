// ฟังก์ชันดึง reservation_id จาก URL
function getReservationIdFromURL() {
  const urlParams = new URLSearchParams(window.location.search);
  return urlParams.get('reservation_id');  // ดึง reservation_id จาก URL
}

// ฟังก์ชันแสดงการเลือกชำระเงิน
function showPaymentOption() {
  const paymentMethod = document.getElementById("payment-method").value;
  const qrCodeDiv = document.getElementById("qr-code");

  console.log("วิธีชำระเงินที่เลือก:", paymentMethod); // Debug ค่า

  // แสดงหรือซ่อน QR code
  qrCodeDiv.style.display = paymentMethod === "qr" ? "block" : "none";
}
document.addEventListener("DOMContentLoaded", function () {
  document.querySelector(".confirm-btn").addEventListener("click", confirmPayment);
});

function confirmPayment() {
  const gettingTableId = document.getElementById("getting-table-id").value;
  const tableId = document.getElementById("table-number").textContent.trim();
  const paymentMethod = document.getElementById("payment-method").value;
  const totalPayment = document.getElementById("final-price").textContent.replace(/,/g, "").trim();

  // ตรวจสอบว่าข้อมูลครบถ้วนหรือไม่
  if (!gettingTableId || !tableId || !totalPayment || paymentMethod === "เลือกวิธีชำระเงิน") {
      alert("กรุณากรอกข้อมูลให้ครบถ้วน");
      return;
  }

  // ส่งข้อมูลการชำระเงินผ่าน fetch ไปยัง save_payment.php
  fetch("save_payment.php", {
    method: "POST",
    headers: {
        "Content-Type": "application/json"
    },
    body: JSON.stringify({
        getting_table_id: gettingTableId,
        payment_method: paymentMethod,
        total_payment: totalPayment
    })
})
.then(response => response.json())  // แปลง response เป็น JSON
.then(data => {
    if (data.success) {
        // ส่งข้อมูลไปที่หน้า Makepayment.php โดยใช้ URL query string
        window.location.replace(`Makepayment.php?getting_table_id=${data.getting_table_id}&payment_method=${data.payment_method}&total_payment=${data.total_payment}`);
    } else {
        console.error("เกิดข้อผิดพลาด: " + data.message);
        alert("เกิดข้อผิดพลาด: " + data.message);
    }
})
.catch(error => {
    console.error("Error:", error);
    alert("เกิดข้อผิดพลาดในการส่งข้อมูล");
});
}

