function loadMenu(event, category) {
  event.preventDefault(); // ป้องกันการเปลี่ยนหน้า
  const loadingIndicator = document.getElementById("loading-indicator");
  const imageGallery = document.getElementById("image-gallery");

  // แสดงแอนิเมชันวงกลม
  loadingIndicator.style.display = "block";
  imageGallery.innerHTML = ""; // ล้างเนื้อหาเก่า

  // สมมุติการโหลดเนื้อหาใหม่
  setTimeout(() => {
    loadingIndicator.style.display = "none"; // ซ่อนวงกลม
    imageGallery.innerHTML = `<div class="image-item">
          <img src="/img/sample.jpg" alt="ตัวอย่าง">
          <p>หมวด ${category}</p>
          <p class="price">100 บาท</p>
      </div>`;
  }, 1000); // โหลดเนื้อหาใน 1 วินาที
}
