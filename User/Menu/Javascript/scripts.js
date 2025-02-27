let orderList = {};

function updateOrder(menuId, itemName, change) {
    if (!orderList[menuId]) {
        orderList[menuId] = { name: itemName, quantity: 0 };
    }
    orderList[menuId].quantity += change;
    if (orderList[menuId].quantity <= 0) {
        delete orderList[menuId];
    }
    renderOrderSummary();
}

function renderOrderSummary() {
    let orderSummary = document.getElementById("order-list");
    orderSummary.innerHTML = "";
    for (let id in orderList) {
        let item = orderList[id];
        orderSummary.innerHTML += `<li>${item.name} x ${item.quantity}</li>`;
    }
}

function submitOrder() {
    alert("สรุปรายการที่เลือก: " + JSON.stringify(orderList));
}
