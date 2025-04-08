document.addEventListener("DOMContentLoaded", () => {
    loadCustomerServiceChart();
    loadPackageOptions("package_customer_service");
    loadPromotionOptions("promotion_customer_service");
    loadGenderOptions("gender_customer_service");
    loadReligionOptions("religion_customer_service");
    loadTableOptions("table_customer_service");
  
    document.getElementById("filterCustomerServiceForm").addEventListener("submit", function (e) {
      e.preventDefault();
      const formData = new FormData(this);
      loadCustomerServiceChart(formData);
  
      const modal = bootstrap.Modal.getInstance(document.getElementById("filterCustomerServiceModal"));
      modal.hide();
    });
  });
  
  let customerChart;
  
  function loadCustomerServiceChart(formData = null) {
    fetch("api/get_customer_service_chart.php", {
      method: "POST",
      body: formData
    })
    .then(res => res.json())
    .then(data => {
      const ctx = document.getElementById("customerServicePie").getContext("2d");
  
      if (customerChart) {
        customerChart.destroy();
      }
  
      customerChart = new Chart(ctx, {
        type: "pie",
        data: {
          labels: data.labels,
          datasets: [{
            label: "จำนวนลูกค้า",
            data: data.values,
            backgroundColor: ["#00bcd4", "#2196f3"],
          }]
        }
      });
    });
  }
  
  // ใช้ function เดิมจาก summary_overview.js แล้วส่ง id select มาใช้แบบ reusable
  function loadPackageOptions(selectId = "package") {
    fetch("api/get_package_options.php")
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
  
  function loadPromotionOptions(selectId = "promotion") {
    fetch("api/get_promotion_options.php")
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
  
  function loadGenderOptions(selectId = "gender") {
    fetch("api/get_gender_options.php")
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
  
  function loadReligionOptions(selectId = "religion") {
    fetch("api/get_religion_options.php")
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
  