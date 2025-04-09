document.addEventListener("DOMContentLoaded", () => {
    loadPackageChart();
    loadPromotionOptions("promotion_package_chart");
    loadGenderOptions("gender_package_chart");
    loadReligionOptions("religion_package_chart");
    loadTableOptions("table_package_chart");
    loadTableOptions("table_package_chart");
  
    document.getElementById("filterPackageChartForm").addEventListener("submit", function (e) {
      e.preventDefault();
      const formData = new FormData(this);
      loadPackageChart(formData);
  
      const modal = bootstrap.Modal.getInstance(document.getElementById("filterPackageChartModal"));
      modal.hide();
    });
  });
  
  let packageChart;
  
  function loadPackageChart(formData = null) {
    fetch("api/get_package_chart.php", {
      method: "POST",
      body: formData
    })
      .then(res => res.json())
      .then(data => {
        const ctx = document.getElementById("packagePieChart").getContext("2d");
  
        if (packageChart) {
          packageChart.destroy();
        }
  
        packageChart = new Chart(ctx, {
          type: "pie",
          data: {
            labels: data.labels,
            datasets: [{
              label: "จำนวนลูกค้า",
              data: data.values,
              backgroundColor: data.colors,
            }]
          }
        });
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
  