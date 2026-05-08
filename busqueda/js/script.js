document.addEventListener("DOMContentLoaded", function () {
    const buscador = document.getElementById("buscador");

    if (buscador) {
        buscador.addEventListener("keyup", function () {
            let texto = this.value.toLowerCase();
            let filas = document.querySelectorAll("#tablaGanado tr");

            filas.forEach(function (fila) {
                let nombre = fila.children[1].textContent.toLowerCase();
                let tipo = fila.children[2].textContent.toLowerCase();
                let raza = fila.children[3].textContent.toLowerCase();

                fila.style.display = (
                    nombre.includes(texto) ||
                    tipo.includes(texto) ||
                    raza.includes(texto)
                ) ? "" : "none";
            });
        });
    }
});

function filtrarTipo(tipo) {
    let filas = document.querySelectorAll("#tablaGanado tr");

    filas.forEach(function(fila) {
        let tipoFila = fila.children[2].innerText.toLowerCase().trim();

        if (tipo === "todos") {
            fila.style.display = "";
        } else if (tipoFila.includes(tipo)) {
            fila.style.display = "";
        } else {
            fila.style.display = "none";
        }
    });
}