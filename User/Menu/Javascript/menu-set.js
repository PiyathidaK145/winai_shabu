document.addEventListener("DOMContentLoaded", () => {
  const menuQuantities = document.querySelectorAll(".menu-quantity");
  const tabs = document.querySelectorAll(".tabs li");
  const items = document.querySelectorAll(".image-item");

  // โหลดจำนวนที่บันทึกจาก localStorage
  loadQuantitiesFromLocalStorage();

  // จัดการปุ่มเพิ่มและลดจำนวน
  menuQuantities.forEach((menu) => {
    const increaseButton = menu.querySelector(".increase");
    const decreaseButton = menu.querySelector(".decrease");
    const quantityElement = menu.querySelector(".quantity");
    const itemName = menu.closest('.image-item').querySelector('p').textContent;

    // กำหนดจำนวนเริ่มต้นจาก localStorage
    quantityElement.textContent = localStorage.getItem(itemName) || 0;

    // ผูก Event สำหรับปุ่มเพิ่มจำนวน
    increaseButton.addEventListener("click", () => {
      let quantity = parseInt(quantityElement.textContent, 10) || 0;
      quantity++;
      quantityElement.textContent = quantity;
      localStorage.setItem(itemName, quantity); // บันทึกไปที่ localStorage
      updateOrderSummary(); // อัปเดตรายการออเดอร์
    });

    // ผูก Event สำหรับปุ่มลดจำนวน
    decreaseButton.addEventListener("click", () => {
      let quantity = parseInt(quantityElement.textContent, 10) || 0;
      if (quantity > 0) {
        quantity--;
        quantityElement.textContent = quantity;
        localStorage.setItem(itemName, quantity); // บันทึกไปที่ localStorage
        updateOrderSummary(); // อัปเดตรายการออเดอร์
      }
    });
  });

  // จัดการการกรองเมนูจากแท็บ
  tabs.forEach((tab) => {
    tab.addEventListener("click", () => {
      const category = tab.getAttribute("data-category");
      filterItems(category);
    });
  });

  // ฟังก์ชันกรองเมนูตามประเภท
  function filterItems(category) {
    items.forEach((item) => {
      if (category === "all" || item.getAttribute("data-category") === category) {
        item.style.display = "block";
      } else {
        item.style.display = "none";
      }
    });
  }

  // ฟังก์ชันโหลดจำนวนจาก localStorage และแสดงจำนวนที่เลือกในเมนู
  function loadQuantitiesFromLocalStorage() {
    menuQuantities.forEach((menu) => {
      const itemName = menu.closest('.image-item').querySelector('p').textContent;
      const quantityElement = menu.querySelector(".quantity");
      const savedQuantity = localStorage.getItem(itemName);

      if (savedQuantity) {
        quantityElement.textContent = savedQuantity;
        quantityElement.style.visibility = savedQuantity > 0 ? 'visible' : 'hidden';
      }
    });
  }

  // ฟังก์ชันอัปเดตรายการออเดอร์ (เช่น การแสดงจำนวนเมนูที่เลือกทั้งหมด)
  function updateOrderSummary() {
    const orderList = document.getElementById('order-list');
    orderList.innerHTML = '';  // ลบรายการเดิม

    // วนลูปผ่าน localStorage และสร้างรายการออเดอร์
    for (let i = 0; i < localStorage.length; i++) {
      const itemName = localStorage.key(i);
      const itemQuantity = localStorage.getItem(itemName);
      if (itemQuantity > 0) {
        const listItem = document.createElement('li');
        listItem.textContent = `${itemName} - ${itemQuantity}`;
        orderList.appendChild(listItem);
      }
    }
  }
});
