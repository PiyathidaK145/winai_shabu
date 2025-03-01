document.addEventListener("DOMContentLoaded", function () {
    function fetchDashboardData(startDate = "", endDate = "") {
        let url = "/winai_shabu/client/api/fetch_dashboard_data.php";
        if (startDate && endDate) {
            url += `?start_date=${startDate}&end_date=${endDate}`;
        }

        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                    return;
                }

                const totalSales = Number(data.total_sales) || 0;
                const totalCustomers = Number(data.total_customers) || 0;
                const totalCost = Number(data.total_cost) || 0;
                const totalProfit = Number(data.total_profit) || (totalSales - totalCost);

                console.log("Debugging: Total Profit =", totalProfit);

                document.getElementById("totalSales").innerText = `${data.total_sales.toLocaleString()} บาท`;
                document.getElementById("totalCustomers").innerText = data.total_customers.toLocaleString();
                document.getElementById("totalCost").innerText = `${data.total_cost.toLocaleString()} บาท`;
                document.getElementById("totalProfit").innerText = `${data.total_profit.toLocaleString()} บาท`;

                let profitElement = document.getElementById("totalProfit");
                profitElement.classList.remove("text-success", "text-danger"); // ล้าง class เก่าก่อน

                if (totalProfit < 0) {
                    profitElement.classList.add("text-danger"); // สีแดงเมื่อขาดทุน
                } else {
                    profitElement.classList.add("text-success"); // สีเขียวเมื่อมีกำไร
                }
            })
            .catch(error => console.error('Error:', error));
    }

    // โหลดข้อมูลเมื่อเปิดหน้า
    fetchDashboardData();

    // ตรวจสอบว่า ID applyFilter มีอยู่จริงก่อนใช้งาน
    const applyFilterBtn = document.getElementById('applyFilter');
    if (applyFilterBtn) {
        applyFilterBtn.addEventListener('click', function () {
            let startDate = document.getElementById('startDate').value;
            let endDate = document.getElementById('endDate').value;

            if (new Date(startDate) > new Date(endDate)) {
                alert("วันที่เริ่มต้นต้องไม่มากกว่าวันที่สิ้นสุด");
                return;
            }

            fetchDashboardData(startDate, endDate);

            // ใช้ Bootstrap Modal API เพื่อปิด Modal
            let modal = bootstrap.Modal.getInstance(document.getElementById('boxFilterModal'));
            if (modal) {
                modal.hide();
            }
        });
    } else {
        console.error("ปุ่ม applyFilter ไม่ถูกพบใน DOM");
    }
});
