window.onload = function () {
    // ดึงค่าทั้งหมดจาก URL
    const urlParams = new URLSearchParams(window.location.search);

    // แสดงค่าทั้งหมดที่ได้รับจาก URL
    console.log("ค่าจาก URL:");
    urlParams.forEach((value, key) => {
        console.log(`${key}: ${value}`);
    });

    const nextButton = document.getElementById("nextButton");

    if (nextButton) {
        nextButton.addEventListener("click", function () {
            // ดึงค่าจาก URL
            const gettingTableId = urlParams.get('getting_table_id');
            const paymentMethod = urlParams.get('payment_method');
            const totalPayment = urlParams.get('total_payment');

            console.log("Getting Table ID (จาก URL): " + gettingTableId);
            console.log("Payment Method (จาก URL): " + paymentMethod);
            console.log("Total Payment (จาก URL): " + totalPayment);

            // หากค่าถูกต้อง ให้ทำการ redirect ไปยัง Receipt.php
            if (gettingTableId && paymentMethod && totalPayment) {
                window.location.href = `../Receipt/Receipt.php?getting_table_id=${encodeURIComponent(gettingTableId)}&payment_method=${encodeURIComponent(paymentMethod)}&total_payment=${encodeURIComponent(totalPayment)}`;
            } else {
                console.log("ข้อมูลไม่ครบถ้วน");
            }
        });
    } else {
        console.log("nextButton not found");
    }

    // เริ่มการหมุนวงกลม
    setTimeout(function () {
        const circleProgress = document.querySelector(".circle-progress");
        const checkmark = document.querySelector(".checkmark");
        const successText = document.querySelector(".success-text");
        const descriptionText = document.querySelector(".description");

        if (circleProgress) {
            circleProgress.style.strokeDashoffset = '0';
        }

        // แสดงเครื่องหมายเช็คและข้อความหลังจากการหมุนวงกลม
        setTimeout(function () {
            if (checkmark) {
                checkmark.style.visibility = 'visible';
                checkmark.style.opacity = '1';
            }

            if (successText) {
                successText.textContent = "ชำระเสร็จสิ้น";
            }

            if (descriptionText) {
                descriptionText.style.display = "block";
                descriptionText.textContent = "กดถัดไปเพื่อรับใบเสร็จ";
            }

            // เปลี่ยนสไตล์ของปุ่มให้พร้อมใช้งาน
            if (nextButton) {
                nextButton.style.backgroundColor = "green";
                nextButton.style.cursor = "pointer";
                nextButton.removeAttribute("disabled");
            }

        }, 3000); // แสดง checkmark หลังจากหมุนเสร็จ 3 วินาที
    }, 1500); // เริ่มหมุนวงกลมหลังจาก 1.5 วินาที
};