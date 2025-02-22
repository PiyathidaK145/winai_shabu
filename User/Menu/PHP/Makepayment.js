 window.onload = function () {
        const circleProgress = document.querySelector(".circle-progress");
        const checkmark = document.querySelector(".checkmark");
        const successText = document.querySelector(".success-text");
        const descriptionText = document.querySelector(".description");
        const nextButton = document.getElementById("nextButton");

        // เริ่มการหมุนวงกลม
        setTimeout(function () {
            circleProgress.style.strokeDashoffset = '0';

            // หลังจากหมุนเสร็จ ให้แสดงเครื่องหมายเช็ค
            setTimeout(function () {
                checkmark.style.visibility = 'visible';
                checkmark.style.opacity = '1';

                // เปลี่ยนข้อความ
                if (successText) {
                    successText.textContent = "ชำระเสร็จสิ้น";
                }

                // เปลี่ยนข้อความ description
                if (descriptionText) {
                    descriptionText.style.display = "block";
                    descriptionText.textContent = "กดถัดไปเพื่อรับใบเสร็จ";
                }

                // เปลี่ยนปุ่มเป็นสีเขียวและกดได้
                nextButton.style.backgroundColor = "green";
                nextButton.style.cursor = "pointer";
                nextButton.removeAttribute("disabled");

                // เพิ่ม Event ให้ปุ่ม เมื่อกดแล้วไปที่ Receipt.php
                nextButton.addEventListener("click", function () {
                    // รับค่าจาก PHP ที่ฝังไว้ใน data attributes
                    const gettingTableId = document.getElementById("nextButton").getAttribute("data-getting-table-id");
                    const paymentMethod = document.getElementById("nextButton").getAttribute("data-payment-method");
                    const totalPayment = document.getElementById("nextButton").getAttribute("data-total-payment");

                    // Redirect ไปที่ Receipt.php พร้อมส่งค่า
                    window.location.href = `Receipt.php?getting_table_id=${encodeURIComponent(gettingTableId)}&payment_method=${encodeURIComponent(paymentMethod)}&total_payment=${encodeURIComponent(totalPayment)}`;
                });

            }, 3000); // แสดง checkmark หลังจากหมุนเสร็จ 3 วินาที
        }, 1500);
    };