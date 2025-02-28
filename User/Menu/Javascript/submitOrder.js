document.addEventListener("DOMContentLoaded", function () {
    let orderList = {};

    function updateOrder(itemId, itemName, quantityChange) {
        if (!orderList[itemId]) {
            orderList[itemId] = { name: itemName, quantity: 0 };
        }
        orderList[itemId].quantity += quantityChange;

        if (orderList[itemId].quantity <= 0) {
            delete orderList[itemId];
        }

        renderOrderList();
    }

    function renderOrderList() {
        const orderListElement = document.getElementById("order-list");
        orderListElement.innerHTML = "";

        for (let itemId in orderList) {
            let item = orderList[itemId];
            let li = document.createElement("li");
            li.textContent = `${item.name} x ${item.quantity}`;
            orderListElement.appendChild(li);
        }
    }

    function submitOrder(reservationId) {
        if (Object.keys(orderList).length === 0) {
            alert("กรุณาเลือกเมนูก่อนสั่ง!");
            return;
        }

        let itemsData = {};
        for (let itemId in orderList) {
            itemsData[itemId] = { quantity: orderList[itemId].quantity };
        }

        let orderData = {
            reservation_id: reservationId,
            items: itemsData
        };

        console.log("🔹 JSON ที่จะส่งไปยัง submitOrder.php:", orderData); // Debug JSON ก่อนส่ง

        fetch("/User/Menu/PHP/submitOrder.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(orderData)
        })
        .then(response => response.text()) // เปลี่ยนจาก response.json() เป็น response.text()
        .then(data => {
            console.log("🔹 Response (RAW):", data);
            try {
                let jsonData = JSON.parse(data); // แปลงเป็น JSON
                console.log("🔹 Response (JSON):", jsonData);

                if (jsonData.success) {
                    alert("บันทึกออเดอร์เรียบร้อย!");
                    orderList = {};
                    renderOrderList();
                } else {
                    alert("เกิดข้อผิดพลาด: " + jsonData.message);
                }
            } catch (error) {
                console.error("❌ JSON Parsing Error:", error);
                alert("❌ ไม่สามารถแปลง JSON ได้");
            }
        })
        .catch(error => {
            console.error("❌ Error:", error);
            alert("❌ ไม่สามารถส่งคำสั่งซื้อได้ กรุณาลองอีกครั้ง");
        });
    }

    window.updateOrder = updateOrder;
    window.submitOrder = submitOrder;
});
