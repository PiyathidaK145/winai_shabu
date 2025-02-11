// ฟังก์ชันแสดงการเลือกชำระเงิน
function showPaymentOption() {
  const paymentMethod = document.getElementById("payment-method").value;
  const qrCodeDiv = document.getElementById("qr-code");

  console.log(paymentMethod); // ดูค่าที่ถูกเลือก

  // แสดงหรือซ่อน QR code
  if (paymentMethod === "qr") {
    qrCodeDiv.style.display = "block";  // แสดงรูป QR
  } else {
    qrCodeDiv.style.display = "none";   // ซ่อนรูป QR
  }
}

// ฟังก์ชันยืนยันการชำระเงิน
function confirmPayment() {
  const paymentMethod = document.getElementById("payment-method").value;
  
  // ตรวจสอบวิธีการชำระเงิน
  if (paymentMethod === "credit") {
    window.location.href = "Makepayment.php"; // ไปยังหน้ารอการตรวจสอบการชำระเงิน
  }
  else if (paymentMethod === "qr") {
    // ส่งข้อมูลสำหรับการชำระเงิน QR
    const paymentData = {
      packageName: document.getElementById("package-name").innerText,
      peopleCount: document.getElementById("people-count").innerText,
      totalPrice: document.getElementById("total-price").innerText,
      discount: document.getElementById("discount").innerText,
      finalPrice: document.getElementById("final-price").innerText
    };

    // เก็บข้อมูลใน localStorage
    localStorage.setItem("paymentData", JSON.stringify(paymentData));

    // ไปยังหน้ารอตรวจสอบ
    window.location.href = "Makepayment.php";
  }
}