function handleTableClick(tableNumber) {
    let tableElement = document.getElementById(`table-${tableNumber}`);

    // ตรวจสอบว่าโต๊ะถูกจองเต็มหรือไม่
    if (tableElement && tableElement.classList.contains('reserved')) {
        alert("โต๊ะนี้ถูกจองเต็มทุกช่วงเวลาแล้ว");
        return;
    }

    // ส่งหมายเลขโต๊ะไปยังหน้า Chosetime.php ผ่าน URL
    window.location.href = "Chosetime.php?table=" + tableNumber;

}

// ตรวจสอบว่า updateTableStatus มีอยู่จริงก่อนเรียกใช้
if (typeof updateTableStatus === "function") {
    updateTableStatus();
}
// เพิ่ม event listener ให้กับแต่ละโต๊ะ
for (let i = 1; i <= 20; i++) {
    document.getElementById(`table-${i}`).addEventListener('click', () => handleTableClick(i));
}
