// ออบเจกต์สำหรับเก็บจำนวนผลไม้
let order = {};

// ฟังก์ชันสำหรับอัปเดตจำนวนผลไม้
function updateOrder(item, change) {
  // ถ้ายังไม่มีรายการใน order ให้ตั้งค่าเริ่มต้นเป็น 0
  if (!order[item]) {
    order[item] = 0;
  }

  // คำนวณจำนวนใหม่
  const newQuantity = order[item] + change;

  // ตรวจสอบว่าจำนวนต้องไม่ต่ำกว่า 0
  if (newQuantity < 0) {
    return; // หยุดทำงานถ้าจำนวนต่ำกว่า 0
  }

  // อัปเดตจำนวนใน order
  order[item] = newQuantity;

  // อัปเดต UI ในเมนู
  const quantityElement = document.getElementById(`quantity-${item}`);
  if (quantityElement) {
    if (newQuantity > 0) {
      quantityElement.textContent = newQuantity;
      quantityElement.style.visibility = "visible"; // แสดงจำนวนเมื่อ > 0
    } else {
      quantityElement.style.visibility = "hidden"; // ซ่อนจำนวนเมื่อ = 0
    }
  }

  // อัปเดต UI ในส่วน "รายการที่เลือก"
  updateOrderList();
}

// ฟังก์ชันสำหรับอัปเดต UI ในส่วน "รายการที่เลือก"
function updateOrderList() {
  const orderList = document.getElementById("order-list");
  orderList.innerHTML = ""; // ล้างรายการเก่าออก

  // วนลูปสร้างรายการใหม่จาก order
  for (const [item, quantity] of Object.entries(order)) {
    if (quantity > 0) {
      const listItem = document.createElement("li");
      listItem.style.display = "flex";
      listItem.style.justifyContent = "space-between";

      listItem.innerHTML = `
                <span>${item}</span>
                <span>${quantity}</span>
            `;
      orderList.appendChild(listItem);
    }
  }
}

// เชื่อมโยงปุ่ม + และ - กับฟังก์ชัน
document.addEventListener("DOMContentLoaded", () => {
  // ปุ่มเพิ่ม
  document.querySelectorAll(".increase").forEach((button) => {
    button.addEventListener("click", () => {
      const item = button.parentElement.querySelector("p").textContent.trim(); // ดึงชื่อผลไม้จาก <p>
      updateOrder(item, 1); // เพิ่มทีละ 1
    });
  });

  // ปุ่มลด
  document.querySelectorAll(".decrease").forEach((button) => {
    button.addEventListener("click", () => {
      const item = button.parentElement.querySelector("p").textContent.trim(); // ดึงชื่อผลไม้จาก <p>
      updateOrder(item, -1); // ลดทีละ 1
    });
  });
});
