window.onload = function () {
  const circleProgress = document.querySelector(".circle-progress");
  const checkmark = document.querySelector(".checkmark");

  // เริ่มเติมเส้นวงกลม
  setTimeout(() => {
    circleProgress.style.strokeDashoffset = "0";
  }, 100);

  // แสดงติ๊กถูกหลังจากเติมวงเต็ม
  setTimeout(() => {
    checkmark.classList.add("show");
  }, 3000); // 3 วินาทีหลังจากวงกลมเต็ม
};
