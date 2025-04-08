document.addEventListener("DOMContentLoaded", () => {
    loadSummaryOverview();
    loadPackageOptions();
    loadPromotionOptions();
    loadGenderOptions();
    loadReligionOptions();
    loadTableOptions("table_summary");

    document.getElementById("filterSummaryForm").addEventListener("submit", function (e) {
        e.preventDefault();
        loadSummaryOverview(new FormData(this));
        const modal = bootstrap.Modal.getInstance(document.getElementById("filterSummaryModal"));
        modal.hide();
    });
});

function loadSummaryOverview(formData = null) {
    let url = "api/get_summary_overview.php";
    let options = { method: "POST" };
    if (formData) {
        options.body = formData;
    }

    fetch(url, options)
        .then(res => res.json()) // ✅ ได้ object แล้ว
        .then(data => {
            console.log("✅ JSON Response:", data); // ✅ ไม่ต้อง parse ซ้ำ
            document.getElementById("total_customers").innerText = data.total_customers;
            document.getElementById("walkin_customers").innerText = data.walkin_customers;
            document.getElementById("reservation_customers").innerText = data.reservation_customers;
            document.getElementById("avg_time").innerText = data.avg_time + " นาที";
            document.getElementById("total_income").innerText = data.total_income + " บาท";

        })
        .catch(err => {
            console.error("❌ JSON Error:", err);
        });
}

function loadPackageOptions() {
    fetch("api/get_package_options.php")
        .then(res => res.json())
        .then(data => {
            const select = document.getElementById("package");
            data.forEach(item => {
                const opt = document.createElement("option");
                opt.value = item.id;
                opt.text = item.name;
                select.appendChild(opt);
            });
        });
}

function loadPromotionOptions() {
    fetch("api/get_promotion_options.php")
        .then(res => res.json())
        .then(data => {
            const select = document.getElementById("promotion");
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

