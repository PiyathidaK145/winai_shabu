// ดึงค่าหมายเลขโต๊ะจาก URL
const urlParams = new URLSearchParams(window.location.search);
const tableNumber = urlParams.get('table');

// อัปเดตหมายเลขโต๊ะใน <h1>
if (tableNumber) {
    document.getElementById('table-number').textContent = `โต๊ะหมายเลข ${tableNumber}`;
}

// อัปเดตลิงก์ในแต่ละช่วงเวลาให้ใช้ tableNumber ที่ดึงมา
const links = document.querySelectorAll('.available a');
links.forEach(link => {
    const url = new URL(link.href);
    url.searchParams.set('table', tableNumber); // เพิ่ม tableNumber ในลิงก์
    link.href = url.toString(); // อัปเดตลิงก์
});

// เช็คว่า URL มีพารามิเตอร์ที่บ่งชี้ว่าการจองถูกยกเลิกหรือไม่
if (urlParams.has('cancel') && urlParams.get('cancel') === 'true') {
    // เปลี่ยนปุ่มที่มีสถานะ 'reserved' ให้เป็น 'จอง'
    const reservedLinks = document.querySelectorAll('.reserved a');
    reservedLinks.forEach(link => {
        link.textContent = "จอง"; // เปลี่ยนข้อความจาก "ยกเลิก" เป็น "จอง"
        link.href = link.href.replace("Cancel.php", "Form.php"); // เปลี่ยนลิงก์จากการยกเลิกเป็นจอง
    });
}
