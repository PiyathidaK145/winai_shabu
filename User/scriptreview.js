// DOM Elements
const stars = document.querySelectorAll(".stars span");
const ratingMessage = document.querySelector(".rating-message");
const tagsContainer = document.querySelector(".tags");
const submitButton = document.getElementById("submit-btn");

// Messages and Tags
const feedback = {
  1: {
    message: "ควรปรับปรุง",
    tags: ["บริการล่าช้า", "อาหารไม่ค่อยสด", "รู้สึกไม่ค่อยคุ้มราคา", "บริการต้องปรับปรุง", "คุณภาพต่ำ", "น้ำซุปไม่อร่อย"],
  },
  2: {
    message: "ไม่ดีเท่าไหร่",
    tags: ["รอนาน", "อาหารบางเมนูไม่สด", "ไม่ค่อยคุ้มราคา", "บริการต้องปรับปรุง", "ของไม่หลากหลาย", "รสชาติธรรมดา"],
  },
  3: {
    message: "พอใช้ได้",
    tags: ["โอเคในภาพรวม", "คุ้มราคาพอสมควร", "บริการพอใช้ได้", "รสชาติพอใช้ได้", "น้ำจิ้มพอใช้ได้", "ของมีเยอะอยู่"],
  },
  4: {
    message: "ชอบเลย",
    tags: ["อาหารหลากหลาย", "บริการรวดเร็ว", "คุ้มค่าราคาอาหาร", "บรรยากาศดี", "น้ำซุปอร่อย", "ของสดดี"],
  },
  5: {
    message: "สุดยอด!",
    tags: ["รสชาติยอดเยี่ยม", "บริการประทับใจ", "คุ้มค่าราคาเกินราคา", "บรรยากาศดี", "น้ำซุปกลมกล่อม", "แนะนำเลย"],
  },
};

// Handle Star Click
stars.forEach((star) => {
  star.addEventListener("click", (e) => {
    const rating = parseInt(e.target.getAttribute("data-value"));

    // Highlight stars
    stars.forEach((s) => s.classList.remove("active"));
    for (let i = 0; i < rating; i++) {
      stars[i].classList.add("active");
    }

    // Update message and tags
    ratingMessage.textContent = feedback[rating].message;
    updateTags(feedback[rating].tags);
  });
});

// Update Tags
function updateTags(tags) {
  tagsContainer.innerHTML = ""; // Clear existing tags
  tags.forEach((tag) => {
    const tagElement = document.createElement("div");
    tagElement.className = "tag";
    tagElement.textContent = tag;

    // Clickable Tags
    tagElement.addEventListener("click", () => {
      tagElement.classList.toggle("selected");
    });

    tagsContainer.appendChild(tagElement);
  });
}
