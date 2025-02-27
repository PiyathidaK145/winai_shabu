// รอให้ DOM โหลดก่อนที่จะผูก Event Listener
document.addEventListener("DOMContentLoaded", function () {
  const incrementButtons = document.querySelectorAll(".increment");
  const decrementButtons = document.querySelectorAll(".decrement");

  // ผูก Event ให้ปุ่มเพิ่มจำนวนและลดจำนวน โดยใช้ฟังก์ชันที่ชัดเจน
  incrementButtons.forEach(button => {
    button.replaceWith(button.cloneNode(true)); // ลบ Event Listener ซ้ำก่อน
    button.addEventListener("click", () => updateOrder(button.dataset.item, 1));
  });

  decrementButtons.forEach(button => {
    button.replaceWith(button.cloneNode(true));
    button.addEventListener("click", () => updateOrder(button.dataset.item, -1));
  });
});

function updateOrder(itemName, change) {
  const quantityElement = document.getElementById(`quantity-${itemName}`);
  const orderList = document.getElementById("order-list");

  if (!quantityElement) {
    console.error(`ไม่พบ element สำหรับ item: ${itemName}`);
    return;
  }

  // ตรวจสอบค่าปัจจุบันในช่องจำนวน
  let currentQuantity = parseInt(quantityElement.textContent.trim(), 10) || 0;

  // คำนวณจำนวนใหม่
  let newQuantity = currentQuantity + change;

  // ป้องกันไม่ให้จำนวนต่ำกว่า 0
  newQuantity = Math.max(newQuantity, 0);

  // อัปเดตจำนวนใน DOM
  quantityElement.textContent = newQuantity;

  // ตรวจสอบรายการใน order-list
  let existingOrderItem = document.querySelector(`#order-list li[data-item='${itemName}']`);

  if (newQuantity > 0) {
    if (!existingOrderItem) {
      // เพิ่มรายการใหม่ใน order-list
      const newOrderItem = document.createElement("li");
      newOrderItem.setAttribute("data-item", itemName);
      newOrderItem.innerHTML = `<span>${itemName}</span><span>x <span class='quantity'>${newQuantity}</span></span>`;
      orderList.appendChild(newOrderItem);
    } else {
      // อัปเดตจำนวนในรายการเดิม
      existingOrderItem.querySelector(".quantity").textContent = newQuantity;
    }
  } else if (existingOrderItem) {
    // ลบรายการเมื่อจำนวนเป็น 0
    orderList.removeChild(existingOrderItem);
  }
}