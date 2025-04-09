document.addEventListener("DOMContentLoaded", () => {
  loadSatisfaction();
  loadPackageOptions("package_satisfaction");
  loadPromotionOptions("promotion_satisfaction");
  loadGenderOptions("gender_satisfaction");
  loadReligionOptions("religion_satisfaction");
  loadTableOptions("table_satisfaction");

  document.getElementById("filterSatisfactionForm").addEventListener("submit", function (e) {
    e.preventDefault();
    loadSatisfaction(new FormData(this));
    bootstrap.Modal.getInstance(document.getElementById("filterSatisfactionModal")).hide();
  });
});

let pieChart;

function loadSatisfaction(formData = null) {
  fetch("api/get_satisfaction_analysis.php", {
    method: "POST",
    body: formData
  })
    .then(res => res.text()) // 🔄 เปลี่ยนจาก .json() เป็น .text()
    .then(data => {
      console.log("RAW RESPONSE:", data); // 🔍 ดูว่าเป็น HTML error หรือเปล่า
      try {
        const parsed = JSON.parse(data); // แปลง JSON ด้วยตัวเอง
        // ✅ ถ้าไม่มี error ดำเนินการต่อ
        document.getElementById("tag_service").innerText = parsed.tags.service;
        document.getElementById("tag_cleanliness").innerText = parsed.tags.cleanliness;
        document.getElementById("tag_food").innerText = parsed.tags.food;
        document.getElementById("tag_other").innerText = parsed.tags.other;
        document.getElementById("tag_price").innerText = parsed.tags.price;
        document.getElementById("avg_satisfaction").innerText = parsed.avg_score;

        const ctx = document.getElementById("satisfactionPieChart").getContext("2d");
        if (pieChart) pieChart.destroy();

        pieChart = new Chart(ctx, {
          type: "pie",
          data: {
            labels: parsed.labels,
            datasets: [{
              label: "ระดับคะแนน",
              data: parsed.values,
              backgroundColor: ['#d32f2f', '#f57c00', '#fbc02d', '#388e3c', '#1976d2']
            }]
          }
        });
      } catch (err) {
        console.error("❌ ไม่สามารถแปลง JSON ได้:", err);
      }
    })
    .catch(error => {
      console.error("❌ fetch error:", error);
    });
}


function loadTableOptions(selectId = "table") {
  fetch("api/get_table_options.php")
    .then(res => res.json())
    .then(data => {
      const select = document.getElementById(selectId);
      data.forEach(item => {
        const opt = document.createElement("option");
        opt.value = item.id;
        opt.text = item.name;
        select.appendChild(opt);
      });
    });
}
