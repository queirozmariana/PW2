document.addEventListener("DOMContentLoaded", function () {
    const sidebar = document.getElementById("sidebar");
    const toggle = document.getElementById("mobileToggle");

    if (toggle && sidebar) {
        toggle.addEventListener("click", function () {
            sidebar.classList.toggle("open");
        });
    }

    document.querySelectorAll("[data-confirm]").forEach(function (element) {
        element.addEventListener("click", function (event) {
            const message = element.getAttribute("data-confirm") || "Tem certeza que deseja continuar?";
            if (!confirm(message)) {
                event.preventDefault();
            }
        });
    });

    document.querySelectorAll(".money-input").forEach(function (input) {
        input.addEventListener("input", function () {
            this.value = this.value.replace(",", ".");
        });
    });

    const searchInput = document.getElementById("searchClientes");
    if (searchInput) {
        searchInput.addEventListener("input", function () {
            const term = this.value.toLowerCase().trim();
            document.querySelectorAll("#tabelaClientes tbody tr[data-search]").forEach(function (row) {
                row.style.display = row.dataset.search.includes(term) ? "" : "none";
            });
        });
    }
});
