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
/*function confirmPayment() {
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
  
}*/
function confirmPayment() {
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
