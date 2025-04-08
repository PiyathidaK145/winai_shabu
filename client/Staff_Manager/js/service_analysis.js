let currentType = "sales"; // default chart type
let chartInstance;

document.addEventListener("DOMContentLoaded", () => {
  loadAllOptions();
  loadTableOptions("table_service_analysis");
  loadChart();
  

  document.getElementById("btn_sales").addEventListener("click", () => switchChart("sales"));
  document.getElementById("btn_customers").addEventListener("click", () => switchChart("customers"));
  document.getElementById("btn_satisfaction").addEventListener("click", () => switchChart("satisfaction"));

  document.getElementById("filterServiceAnalysisForm").addEventListener("submit", function (e) {
    e.preventDefault();
    loadChart(new FormData(this));
    bootstrap.Modal.getInstance(document.getElementById("filterServiceAnalysisModal")).hide();
  });
});

function switchChart(type) {
  currentType = type;
  document.querySelectorAll(".btn-group button").forEach(btn => btn.classList.remove("active"));
  document.getElementById(`btn_${type}`).classList.add("active");
  loadChart();
}

function loadChart(formData = null) {
  const chartMap = {
    sales: "api/chart_sales.php",
    customers: "api/chart_customers.php",
    satisfaction: "api/chart_satisfaction.php"
  };

  const url = chartMap[currentType];
  fetch(url, {
    method: "POST",
    body: formData
  })
    .then(res => res.json())
    .then(data => {
      const ctx = document.getElementById("analysisChart").getContext("2d");
      if (chartInstance) chartInstance.destroy();

      chartInstance = new Chart(ctx, {
        type: "bar",
        data: {
          labels: data.labels,
          datasets: [{
            label: data.label,
            data: data.values,
            backgroundColor: "#42a5f5"
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: { display: false }
          },
          scales: {
            y: {
              beginAtZero: true
            }
          }
        }
      });
    });
}

function loadAllOptions() {
  loadPackageOptions("package_service");
  loadPromotionOptions("promotion_service");
  loadGenderOptions("gender_service");
  loadReligionOptions("religion_service");
  loadTableOptions("table_service");
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
