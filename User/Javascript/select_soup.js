// เก็บรายการน้ำซุปที่เลือก
let selectedSoups = [];

function toggleSoupSelection(element, soupName) {
  const soupIndex = selectedSoups.indexOf(soupName);

  // หากน้ำซุปนี้ถูกเลือกแล้ว ให้ยกเลิกการเลือก
  if (soupIndex > -1) {
    selectedSoups.splice(soupIndex, 1); // เอาออกจากรายการ
    element.classList.remove("selected");
  } else {
    // ถ้าเลือกได้ไม่เกิน 2 อย่าง ให้เพิ่มน้ำซุปใหม่
    if (selectedSoups.length < 2) {
      selectedSoups.push(soupName);
      element.classList.add("selected");
    } else {
      // ถ้าเลือกครบ 2 อย่างแล้ว ให้แสดงแจ้งเตือน
      alert("คุณสามารถเลือกน้ำซุปได้สูงสุด 2 อย่างเท่านั้น");
    }
  }

  // อัปเดตปุ่มยืนยัน
  updateConfirmButton();
}

function updateConfirmButton() {
  const confirmButton = document.getElementById("confirm-button");

  // ปุ่มจะเปิดใช้งานเมื่อมีน้ำซุปอย่างน้อย 1 อย่างถูกเลือก
  if (selectedSoups.length > 0) {
    confirmButton.disabled = false;
  } else {
    confirmButton.disabled = true;
  }
}

function confirmSelection() {
  if (selectedSoups.length > 0) {
    alert("คุณได้เลือกน้ำซุป: " + selectedSoups.join(", "));
  } else {
    alert("กรุณาเลือกน้ำซุปอย่างน้อย 1 อย่าง");
  }
}
