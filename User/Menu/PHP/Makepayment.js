window.onload = function () {
  const circleProgress = document.querySelector(".circle-progress");
  const checkmark = document.querySelector(".checkmark");
  const successText = document.querySelector(".success-text");
  const descriptionText = document.querySelector(".description");

  // เริ่มการหมุนวงกลม
  setTimeout(function () {
      // เปลี่ยนค่า stroke-dashoffset ให้เป็น 0 เพื่อให้วงกลมหมุนครบ
      circleProgress.style.strokeDashoffset = '0';

      // หลังจากหมุนเสร็จแล้ว ให้แสดงเครื่องหมายเช็ค
      setTimeout(function () {
          checkmark.style.visibility = 'visible';
          checkmark.style.opacity = '1';

          // เปลี่ยนข้อความใน success-text
          if (successText) {
              successText.textContent = "ชำระเสร็จสิ้น";
          }

          // ลบข้อความใน description-text
          if (descriptionText) {
              descriptionText.style.display = "none";  // ซ่อนข้อความ description
          }

          // เปลี่ยนหน้าไปที่ Receipt.php ทันทีหลังจากแสดงเครื่องหมายเช็ค
          const url = "Receipt.php?getting_table_id=" + encodeURIComponent("<?php echo $getting_table_id; ?>") +
                      "&payment_method=" + encodeURIComponent("<?php echo $payment_method; ?>") +
                      "&total_payment=" + encodeURIComponent("<?php echo $total_payment; ?>");
          console.log("Redirecting to: " + url);
          window.location.href = url;  // เปลี่ยนหน้าไปที่ Receipt.php
      }, 500); // หลังจากหมุนเสร็จ 500ms จะแสดงเครื่องหมายเช็ค
  }, 100); // เริ่มหมุนทันที
};