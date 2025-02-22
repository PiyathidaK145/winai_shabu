// ฟังก์ชันแสดงการเลือกชำระเงิน
function showPaymentOption() {
  const paymentMethod = document.getElementById("payment-method").value;
  const qrCodeDiv = document.getElementById("qr-code");

  console.log("เลือกวิธีชำระเงิน:", paymentMethod);

  // แสดงหรือซ่อน QR Code ตามตัวเลือก
  qrCodeDiv.style.display = (paymentMethod === "qr") ? "block" : "none";
}

// ฟังก์ชันยืนยันการชำระเงิน
function confirmPayment() {
  const tableID = document.getElementById("table-number").innerText;
  const packageID = document.getElementById("package-id").value;
  const promotionID = document.getElementById("promotion-id").value;
  const packagePrice = document.getElementById("package-price").value;
  const paymentMethod = document.getElementById("payment-method").value;

  // ตรวจสอบว่าผู้ใช้เลือกวิธีชำระเงิน
  if (paymentMethod === "เลือกวิธีชำระเงิน") {
      alert("❌ กรุณาเลือกวิธีชำระเงิน");
      return;
  }

  // สร้าง JSON ข้อมูล
  const paymentData = {
      table_id: tableID,
      package_id: packageID,
      promotion_id: promotionID || null,  // ส่งค่า null หากไม่มีส่วนลด
      package_price: packagePrice,
      payment_method: paymentMethod
  };

  console.log("📤 กำลังส่งข้อมูลการชำระเงิน...", paymentData);

  // ส่งข้อมูลไปที่ `process_payment.php`
  fetch("process_payment.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(paymentData)
  })
  .then(response => response.json())
  .then(result => {
      console.log("📥 คำตอบจากเซิร์ฟเวอร์:", result);
      if (result.success) {
          alert("✅ ชำระเงินสำเร็จ!");
          window.location.href = "success_page.php"; // ไปยังหน้าชำระเงินสำเร็จ
      } else {
          alert("❌ เกิดข้อผิดพลาด: " + result.message);
      }
  })
  .catch(error => {
      console.error("🚨 Error:", error);
      alert("❌ ไม่สามารถทำรายการได้ กรุณาลองใหม่!");
  });
}
