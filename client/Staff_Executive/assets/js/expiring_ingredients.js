document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("expiryFilterForm");
    if (form) {
        form.addEventListener("submit", async function (e) {
            e.preventDefault();

            const formData = new FormData(this);
            const response = await fetch("fetch_expiring_ingredients.php", {
                method: "POST",
                body: formData
            });

            const html = await response.text();
            document.querySelector("#expiringTableBody").innerHTML = html;

            const modal = bootstrap.Modal.getInstance(document.getElementById("expiryFilterModal"));
            if (modal) modal.hide();
        });
    }
});
