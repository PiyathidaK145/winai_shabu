function handleTableClick(tableNumber) {
    // ส่งหมายเลขโต๊ะไปยังหน้า Choosetime.php ผ่าน URL
    window.location.href = `Chosetime.php?table=${tableNumber}`;
}
function handleTableClick(tableNumber) {
    let tableElement = document.getElementById(`table-${tableNumber}`);
    
    // ถ้าโต๊ะเป็นสีเทา (จองเต็มแล้ว) ให้ปิดการคลิก
    if (tableElement.classList.contains('reserved')) {
        alert("โต๊ะนี้ถูกจองเต็มทุกช่วงเวลาแล้ว");
        return;
    }

    // ส่งหมายเลขโต๊ะไปยังหน้า Chosetime.php ผ่าน URL
    window.location.href = `Chosetime.php?table=${tableNumber}`;
}

// เรียกฟังก์ชันเมื่อโหลดหน้า
updateTableStatus();