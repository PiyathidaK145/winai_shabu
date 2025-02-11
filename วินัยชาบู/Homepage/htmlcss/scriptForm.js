$(document).ready(function () {
    $("#first_name, #last_name").keyup(function () {
        var firstName = $("#first_name").val();  // ดึงค่าชื่อ
        var lastName = $("#last_name").val();    // ดึงค่านามสกุล

        console.log("ชื่อ: " + firstName + ", นามสกุล: " + lastName);

        // ลบข้อความเก่าออกก่อน เพื่อไม่ให้ซ้อนกัน
        $("#name_status").remove();

        // ตรวจสอบว่าชื่อและนามสกุลถูกกรอก
        if (firstName.length > 0 && lastName.length > 0) {
            $.ajax({
                url: "check_member.php",
                method: "POST",
                data: { first_name: firstName, last_name: lastName },
                success: function(response) {
                    console.log(response); // ดูคำตอบจาก PHP

                    var statusMessage = $("<span id='name_status'></span>").css({
                        "display": "block",
                        "color": response === "exists" ? "green" : "red",
                        "margin-top": "5px"
                    });

                    if (response === "exists") {
                        statusMessage.html("✅ ชื่อและนามสกุลถูกต้อง");
                        $("#submitBtn").prop("disabled", false); // เปิดปุ่ม "จอง"
                    } else {
                        statusMessage.html("❌ ไม่พบข้อมูลในระบบ");
                        $("#submitBtn").prop("disabled", true); // ปิดปุ่ม "จอง"
                    }

                    // แสดงข้อความใต้กล่องกรอกนามสกุล
                    $("#last_name").after(statusMessage);
                },
                error: function(xhr, status, error) {
                    console.log("เกิดข้อผิดพลาด: " + error);
                }
            });
        } else {
            // หากยังไม่กรอกชื่อหรือ นามสกุล
            var errorMessage = $("<span id='name_status' style='color: red; display: block; margin-top: 5px;'>❌ กรุณากรอกชื่อและนามสกุล</span>");
            $("#last_name").after(errorMessage);
            $("#submitBtn").prop("disabled", true); // ปิดปุ่ม "จอง"
        }
    });
});

function goBack() {
    window.location.href = "Homepage.php";  // เปลี่ยนเส้นทางไปยัง Homepage.php
}

