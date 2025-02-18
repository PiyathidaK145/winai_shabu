// ดึงข้อมูลจาก URL Parameters
const urlParams = new URLSearchParams(window.location.search);
const name = urlParams.get('name') || '';
const phone = urlParams.get('phone') || '';
const people = urlParams.get('people') || '';
const time = urlParams.get('time') || '';
const table = urlParams.get('table') || ''; // รับค่าเลขโต๊ะ
const lastName = urlParams.get('last_name') || ''; // รับค่านามสกุล

// แสดงข้อมูลที่ดึงมา
document.getElementById('displayName').textContent = name;
document.getElementById('displayPhone').textContent = phone;
document.getElementById('displayPeople').textContent = people;
document.getElementById('displayTime').textContent = time;
document.getElementById('displayTable').textContent = table; // แสดงหมายเลขโต๊ะ
document.getElementById('displayLastName').textContent = lastName; // แสดงนามสกุล

// เติมค่าลงในฟิลด์ซ่อน
document.getElementById('name').value = name;
document.getElementById('phone').value = phone;
document.getElementById('people').value = people;
document.getElementById('time').value = time;
document.getElementById('table').value = table; // เติมหมายเลขโต๊ะ
document.getElementById('last_name').value = lastName; // เติมค่านามสกุล

// ตั้งค่าปุ่ม "แก้ไข" ให้กลับไปหน้าที่ผ่านมา
document.getElementById('backButton').addEventListener('click', function () {
    try {
        history.back();
    } catch (error) {
        console.error('Error going back:', error);
    }
});
