function handleTableClick(tableNumber) {
    // ส่งหมายเลขโต๊ะไปยังหน้า Choosetime.php ผ่าน URL
    window.location.href = `Chosetime.php?table=${tableNumber}`;
}

// เรียกฟังก์ชันเมื่อโหลดหน้า
updateTableStatus();