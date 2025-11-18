// 🌙 Script global de cambio de modo claro / oscuro
document.addEventListener("DOMContentLoaded", function () {
    const btn = document.getElementById("modoBtn");
    const body = document.body;

    if (!btn) return; // Si no hay botón, no hacer nada

    // Cargar modo guardado
    const savedMode = localStorage.getItem("modoTema");

    if (savedMode === "oscuro") {
        body.classList.add("dark-mode");
        btn.textContent = "🌞 Modo claro";
    } else {
        body.classList.remove("dark-mode");
        btn.textContent = "🌙 Modo oscuro";
    }

    // Al hacer clic en el botón
    btn.addEventListener("click", () => {
        body.classList.toggle("dark-mode");

        if (body.classList.contains("dark-mode")) {
            btn.textContent = "🌞 Modo claro";
            localStorage.setItem("modoTema", "oscuro");
        } else {
            btn.textContent = "🌙 Modo oscuro";
            localStorage.setItem("modoTema", "claro");
        }
    });
});
