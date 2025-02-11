function submitOrder() {
    // ตรวจสอบว่ามีสินค้าในออเดอร์หรือไม่
    if (Object.keys(order).length === 0) {
        alert("กรุณาเลือกสินค้าอย่างน้อย 1 รายการก่อนสั่งออเดอร์!");
        return;
    }

    // สร้างข้อความแสดงรายการสินค้า
    let orderSummary = "คุณได้สั่งออเดอร์ดังนี้:\n";
    for (const [item, quantity] of Object.entries(order)) {
        orderSummary += `${item}: ${quantity} ชิ้น\n`;
    }

    // แสดงข้อความแจ้งเตือน
    alert(orderSummary + "\nออเดอร์กำลังนำเข้าสู่ครัว!");
}