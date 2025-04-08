document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("importFilterForm");
    if (form) {
        console.log("✅ importFilterForm detected");

        form.addEventListener("submit", async function (e) {
            e.preventDefault();
            console.log("🚀 Submitting form");

            const formData = new FormData(this);

            try {
                const response = await fetch("fetch_recent_imports.php", {
                    method: "POST",
                    body: formData
                });

                const html = await response.text();
                document.querySelector("#recentImportsBody").innerHTML = html;

                // ปิด modal ด้วยปุ่มปิด (ทางเลือกที่ไม่พึ่ง bootstrap.Modal)
                document.querySelector('#importFilterModal .btn-close')?.click();

                console.log("✅ Updated import list and closed modal");
            } catch (err) {
                console.error("❌ Fetch error:", err);
            }
        });
    } else {
        console.warn("⚠️ importFilterForm not found in DOM");
    }
});
