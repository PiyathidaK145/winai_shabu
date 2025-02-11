/*document.addEventListener("DOMContentLoaded", function () {
    document.querySelector(".confirm").addEventListener("click", function (event) {
        // ป้องกันไม่ให้กดปุ่มหลายครั้ง
        event.preventDefault();

        // ตรวจสอบว่ามีข้อมูลหรือไม่
        let table = document.getElementById("table").value;
        let name = document.getElementById("name").value;
        let people = document.getElementById("people").value;
        let time = document.getElementById("time").value;

        if (!table || !name || !people || !time) {
            alert("กรุณาตรวจสอบข้อมูลให้ครบก่อนทำการจอง!");
            return;
        }

        // ถ้าข้อมูลครบ ส่งฟอร์มไป Booking.php
        document.querySelector("form").submit();
    });
});
*/