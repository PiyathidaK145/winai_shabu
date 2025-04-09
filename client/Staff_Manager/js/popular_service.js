document.addEventListener("DOMContentLoaded", () => {
    loadPopularService();
    loadGenderOptions("gender_popular");
    loadReligionOptions("religion_popular");
    loadTableOptions("table_popular");
      
    document.getElementById("filterPopularServiceForm").addEventListener("submit", function (e) {
      e.preventDefault();
      const formData = new FormData(this);
      loadPopularService(formData);
  
      const modal = bootstrap.Modal.getInstance(document.getElementById("filterPopularServiceModal"));
      modal.hide();
    });
  });
  
  function loadPopularService(formData = null) {
    fetch("api/get_popular_service.php", {
      method: "POST",
      body: formData
    })
      .then(res => res.json())
      .then(data => {
        document.getElementById("popular_package").innerText = data.popular_package || '-';
        document.getElementById("popular_promotion").innerText = data.popular_promotion || '-';
        document.getElementById("popular_table").innerText = data.popular_table || '-';
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


  