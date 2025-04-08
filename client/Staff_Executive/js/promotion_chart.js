document.addEventListener("DOMContentLoaded", () => {
    loadPromotionChart();
    loadPackageOptions("package_promotion_chart");
    loadGenderOptions("gender_promotion_chart");
    loadReligionOptions("religion_promotion_chart");
    loadTableOptions("table_promotion_chart");
  
    document.getElementById("filterPromotionChartForm").addEventListener("submit", function (e) {
      e.preventDefault();
      const formData = new FormData(this);
      loadPromotionChart(formData);
  
      const modal = bootstrap.Modal.getInstance(document.getElementById("filterPromotionChartModal"));
      modal.hide();
    });
  });
  
  let promotionChart;
  
  function loadPromotionChart(formData = null) {
    fetch("api/get_promotion_chart.php", {
      method: "POST",
      body: formData
    })
      .then(res => res.json())
      .then(data => {
        const ctx = document.getElementById("promotionPieChart").getContext("2d");
  
        if (promotionChart) {
          promotionChart.destroy();
        }
  
        promotionChart = new Chart(ctx, {
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


  