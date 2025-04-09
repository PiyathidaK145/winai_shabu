function handleTableClick(tableNumber) {
    let tableElement = document.getElementById(`table-${tableNumber}`);

    // ตรวจสอบว่าโต๊ะถูกจองเต็มหรือไม่
    if (tableElement && tableElement.classList.contains('reserved')) {
        alert("โต๊ะนี้ถูกจองเต็มทุกช่วงเวลาแล้ว");
        return;
    }

    // ส่งหมายเลขโต๊ะไปยังหน้า Chosetime.php ผ่าน URL
    window.location.href = "../Reservation/Chosetime.php?table=" + tableNumber;

}

// ตรวจสอบว่า updateTableStatus มีอยู่จริงก่อนเรียกใช้
if (typeof updateTableStatus === "function") {
    updateTableStatus();
}
// เพิ่ม event listener ให้กับแต่ละโต๊ะ
for (let i = 1; i <= 20; i++) {
    document.getElementById(`table-${i}`).addEventListener('click', () => handleTableClick(i));
}

// ตัวแปรเพื่อเก็บหมายเลขปัจจุบันของสไลด์
let slideIndex = 0;

// ฟังก์ชันสำหรับแสดงสไลด์ถัดไป
function showSlides() {
  let slides = document.getElementsByClassName("mySlides");
  for (let i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";  
  }
  slideIndex++;
  if (slideIndex > slides.length) {slideIndex = 1}    
  slides[slideIndex-1].style.display = "block";  
  setTimeout(showSlides, 2000); // เปลี่ยนสไลด์ทุก 2 วินาที
}

// เรียกใช้ฟังก์ชันเมื่อโหลดหน้าเว็บเสร็จ
window.onload = function() {
  showSlides();
};
