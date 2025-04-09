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
  qrCodeDiv.style.display = paymentMethod === "QR prompay" ? "block" : "none";
}

document.addEventListener("DOMContentLoaded", function () {
  const confirmButton = document.querySelector(".confirm-btn"); // แก้ไขการเลือกปุ่ม

  if (confirmButton && !confirmButton.hasListener) { // ตรวจสอบว่ามีปุ่ม และยังไม่มี event listener
    confirmButton.addEventListener("click", confirmPayment);
    confirmButton.hasListener = true;  // เพิ่ม flag เพื่อตรวจสอบ
  }
});

let isFetching = false;

function confirmPayment() {
  if (isFetching) return;  // ถ้ากำลังส่งข้อมูลอยู่แล้ว ให้หยุดทำงาน

  isFetching = true;  // ตั้งค่ากำลังทำการส่งข้อมูล

  const gettingTableId = document.getElementById("getting-table-id")?.value;
  const tableId = document.getElementById("table-number")?.textContent.trim();
  const paymentMethod = document.getElementById("payment-method")?.value;
  const totalPayment = document.getElementById("final-price")?.textContent.replace(/,/g, "").trim();

  if (!gettingTableId || !tableId || !totalPayment || paymentMethod === "เลือกวิธีชำระเงิน") {
    alert("กรุณากรอกข้อมูลให้ครบถ้วน");
    isFetching = false;
    return;
  }

  fetch("http://localhost:8081/winai_shabu-main/User/PHP/Payment/save_payment.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
      getting_table_id: gettingTableId,
      payment_method: paymentMethod,
      total_payment: totalPayment
    })
  })
    .then(response => {
      if (!response.ok) {
        throw new Error('Server responded with status ' + response.status);
      }
      return response.text();  // รับข้อมูลเป็นข้อความ
    })
    .then(text => {
      console.log("Raw Response:", text);
      if (text) {
        try {
          return JSON.parse(text);  // แปลงเป็น JSON ถ้ามีข้อมูล
        } catch (e) {
          throw new Error('Error parsing JSON: ' + e.message);
        }
      } else {
        throw new Error('Empty response from server');
      }
    })
    .then(data => {
      console.log("Parsed JSON:", data);
      if (data.success) {
        window.location.replace(`../Payment/Makepayment.php?getting_table_id=${data.getting_table_id}&payment_method=${data.payment_method}&total_payment=${data.total_payment}`);
      } else {
        console.error("เกิดข้อผิดพลาด: " + data.message);
        alert("เกิดข้อผิดพลาด: " + data.message);
      }
    })
    .catch(error => {
      console.error("Error:", error);
      alert("เกิดข้อผิดพลาดในการส่งข้อมูล: " + error.message);
    });
}