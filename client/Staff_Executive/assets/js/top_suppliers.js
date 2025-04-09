document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("supplierFilterForm");
    if (form) {
        form.addEventListener("submit", async (e) => {
            e.preventDefault();

            const formData = new FormData(form);
            const response = await fetch("fetch_top_suppliers.php", {
                method: "POST",
                body: formData,
            });

            const html = await response.text();
            document.querySelector(".top-suppliers-container .row").innerHTML = html;

            const modal = bootstrap.Modal.getInstance(document.getElementById("supplierFilterModal"));
            if (modal) modal.hide();
        });
    }
});
