document.addEventListener("DOMContentLoaded", function () {
  const stars = document.querySelectorAll(".stars span");
  const ratingMessage = document.querySelector(".rating-message");
  const tagsContainer = document.getElementById("tags-container");
  const submitButton = document.getElementById("submit-btn");
  const reviewForm = document.getElementById("review-form");
  const receiptInput = document.getElementById("receipt_id");

  let selectedTags = {};
  let rating = 0;

  // 🔹 ดึงค่า receipt_id จาก URL และเซ็ตให้ input hidden
  const urlParams = new URLSearchParams(window.location.search);
  if (!receiptInput.value.trim()) {
      receiptInput.value = urlParams.get("receipt_id") || "";
  }
  console.log("🔍 ตรวจสอบค่า receipt_id:", receiptInput.value);

  if (!receiptInput.value.trim()) {
      alert("❌ ไม่พบ receipt_id!");
      return;
  }

  // 🔹 Feedback ที่รวมแท็กทั้งหมด
  const feedback = {
      1: { message: "ควรปรับปรุง", tags: [
          { tag_name: "ช้อน-ตะเกียบไม่สะอาด", type: "tag_clean_id", id: 9 },
          { tag_name: "จานชามมีกลิ่น", type: "tag_clean_id", id: 10 },
          { tag_name: "เนื้อหมดอายุ", type: "tag_food_id", id: 19 },
          { tag_name: "ผักไม่ล้าง", type: "tag_food_id", id: 20 },
          { tag_name: "แอร์ร้อน", type: "tag_other_id", id: 49 },
          { tag_name: "ควันเยอะ กลิ่นติดตัว", type: "tag_other_id", id: 50 },
          { tag_name: "ราคาแพงเกินไป", type: "tag_price_id", id: 29 },
          { tag_name: "พนักงานหงุดหงิดใส่ลูกค้า", type: "tag_service_id", id: 40 },
      ]},
      2: { message: "ไม่ดีเท่าไหร่", tags: [
          { tag_name: "พื้นร้านลื่นเล็กน้อย", type: "tag_clean_id", id: 7 },
          { tag_name: "โต๊ะไม่เช็ดให้ก่อนนั่ง", type: "tag_clean_id", id: 8 },
          { tag_name: "อาหารบางอย่างหมดไว", type: "tag_food_id", id: 17 },
          { tag_name: "ไม่มีที่จอดรถ", type: "tag_other_id", id: 47 },
          { tag_name: "เทียบกับคุณภาพไม่คุ้ม", type: "tag_price_id", id: 28 },
          { tag_name: "พนักงานน้อยเกินไป", type: "tag_service_id", id: 36 },
      ]},
      3: { message: "พอใช้ได้", tags: [
          { tag_name: "หม้อมีคราบเล็กน้อย", type: "tag_clean_id", id: 6 },
          { tag_name: "เติมของช้า", type: "tag_food_id", id: 16 },
          { tag_name: "คนเยอะ รอคิวนาน", type: "tag_other_id", id: 46 },
          { tag_name: "มีค่าบริการเพิ่ม", type: "tag_price_id", id: 26 },
          { tag_name: "บริการช้า", type: "tag_service_id", id: 37 },
      ]},
      4: { message: "ชอบเลย", tags: [
          { tag_name: "มีพนักงานเช็ดโต๊ะบ่อย", type: "tag_clean_id", id: 5 },
          { tag_name: "มีระบบทำความสะอาดดี", type: "tag_clean_id", id: 4 },
          { tag_name: "ผักสดสะอาด", type: "tag_food_id", id: 12 },
          { tag_name: "น้ำซุปอร่อย", type: "tag_food_id", id: 13 },
          { tag_name: "มีที่จอดรถ", type: "tag_other_id", id: 42 },
          { tag_name: "มีโต๊ะเยอะ", type: "tag_other_id", id: 43 },
          { tag_name: "อาหารสมราคา", type: "tag_price_id", id: 25 },
          { tag_name: "เติมน้ำซุปให้เอง", type: "tag_service_id", id: 35 },
      ]},
      5: { message: "สุดยอด!", tags: [
          { tag_name: "สะอาดมาก", type: "tag_clean_id", id: 1 },
          { tag_name: "โต๊ะสะอาด", type: "tag_clean_id", id: 2 },
          { tag_name: "อุปกรณ์ครบ สะอาด", type: "tag_clean_id", id: 3 },
          { tag_name: "คุณภาพเนื้อดี", type: "tag_food_id", id: 11 },
          { tag_name: "น้ำจิ้มเด็ด", type: "tag_food_id", id: 14 },
          { tag_name: "บรรยากาศดี", type: "tag_other_id", id: 41 },
          { tag_name: "นั่งสบาย ไม่อึดอัด", type: "tag_other_id", id: 44 },
          { tag_name: "คุ้มค่ามาก", type: "tag_price_id", id: 21 },
          { tag_name: "อร่อย ราคาดี", type: "tag_price_id", id: 22 },
          { tag_name: "บุฟเฟ่ต์ราคาถูก", type: "tag_price_id", id: 23 },
          { tag_name: "โปรโมชั่นคุ้ม", type: "tag_price_id", id: 24 },
          { tag_name: "ยิ้มแย้มแจ่มใส", type: "tag_service_id", id: 32 },
          { tag_name: "ดูแลลูกค้าดี", type: "tag_service_id", id: 33 },
          { tag_name: "เรียกแล้วมาตลอด", type: "tag_service_id", id: 34 },
      ]}
  };

  // 🔹 กำหนดคะแนนที่ผู้ใช้เลือก
  stars.forEach((star) => {
      star.addEventListener("click", function () {
          rating = parseInt(this.getAttribute("data-value"));
          stars.forEach(s => s.classList.remove("active"));
          for (let i = 0; i < rating; i++) {
              stars[i].classList.add("active");
          }
          ratingMessage.textContent = feedback[rating]?.message || "ให้คะแนน";
          updateTags(feedback[rating]?.tags || []);
      });
  });

  // 🔹 ฟังก์ชันแสดง tags ที่เกี่ยวข้องกับ rating
  function updateTags(tags) {
      tagsContainer.innerHTML = "";
      selectedTags = {};
      tags.forEach((tag) => {
          const tagElement = document.createElement("div");
          tagElement.className = "tag";
          tagElement.textContent = tag.tag_name;
          tagElement.dataset.type = tag.type;
          tagElement.dataset.id = tag.id;
          tagElement.addEventListener("click", function () {
              if (this.classList.contains("selected")) {
                  delete selectedTags[this.dataset.type];
                  this.classList.remove("selected");
              } else {
                  selectedTags[this.dataset.type] = this.dataset.id;
                  this.classList.add("selected");
              }
          });
          tagsContainer.appendChild(tagElement);
      });
  }

  // 🔹 เมื่อกดปุ่มส่ง
  submitButton.addEventListener("click", function (event) {
      event.preventDefault();

      // ตรวจสอบ review ว่ามีค่าหรือไม่
      const reviewText = document.querySelector('textarea[name="review"]').value;
      if (reviewText.trim() === '') {
          alert('❌ กรุณากรอกรีวิว');
          return;
      }

      // สร้าง FormData และเพิ่มค่า tag_id
      const formData = new FormData(reviewForm);
      formData.append('rating', rating);
      formData.append('receipt_id', receiptInput.value);

      Object.entries(selectedTags).forEach(([key, value]) => {
          formData.append(key, value);
      });

      // ส่งข้อมูลไปยัง PHP
      fetch('review.php', {
          method: 'POST',
          body: formData
      })
      .then(response => response.text())
      .then(data => {
          console.log("📌 Response:", data);
          window.location.href = 'thankyou.php';
      })
      .catch(error => console.error("❌ Error:", error));
  });
});
