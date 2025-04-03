// ฟังก์ชันเพิ่มหรือลดจำนวนเมนู
function updateOrder(itemName, action) {
    let order = JSON.parse(localStorage.getItem("order")) || [];

    if (action === 1) {
        // เพิ่มจำนวนเมนู
        let item = order.find(o => o.name === itemName);
        if (item) {
            item.quantity++;
        } else {
            order.push({ name: itemName, quantity: 1 });
        }
    } else if (action === 0) {
        // ลดจำนวนเมนูหรือเอาออกจากรายการ
        let item = order.find(o => o.name === itemName);
        if (item) {
            item.quantity--;
            if (item.quantity <= 0) {
                order = order.filter(o => o.name !== itemName);
            }
        }
    }

    localStorage.setItem("order", JSON.stringify(order));
    displayOrder();
}

// ฟังก์ชันแสดงรายการที่เลือก
function displayOrder() {
    let order = JSON.parse(localStorage.getItem("order")) || [];
    const orderList = document.getElementById("order-list");

    orderList.innerHTML = ""; // ลบรายการเก่าทิ้ง

    order.forEach(item => {
        let listItem = document.createElement("li");
        listItem.textContent = `${item.name} - ${item.quantity}`;
        orderList.appendChild(listItem);
    });
}

// เรียกใช้งาน displayOrder เมื่อหน้าโหลด
window.onload = function() {
    displayOrder();
};