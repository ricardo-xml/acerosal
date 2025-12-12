document.addEventListener("DOMContentLoaded", () => {

    const inputBuscar = document.getElementById("buscar_pieza");
    const contResultados = document.getElementById("resultadosPiezas");
    const resumenPieza = document.getElementById("resumenPieza");
    const tbodyKardex = document.getElementById("tbodyKardex");
    const hiddenPiezaId = document.getElementById("piezaSeleccionadaId");
    const btnPdf = document.getElementById("btnExportarPdf");
    const pdfBase = document.getElementById("kardexPdfBase").value;

    let timeout = null;

    // AUTOCOMPLETE
    inputBuscar.addEventListener("keyup", () => {
        const q = inputBuscar.value.trim();

        if (timeout) clearTimeout(timeout);

        if (q.length < 2) {
            contResultados.innerHTML = "";
            return;
        }

        timeout = setTimeout(async () => {
            const res = await fetch(`/inventario/kardex/buscar-pieza?q=${encodeURIComponent(q)}`);
            const data = await res.json();

            contResultados.innerHTML = "";

            if (!data.length) {
                contResultados.innerHTML = `<div class="kardex-autocomplete-item">Sin resultados</div>`;
                return;
            }

            data.forEach(item => {
                const div = document.createElement("div");
                div.classList.add("kardex-autocomplete-item");
                div.dataset.id = item.id_pieza;
                div.dataset.codigo = item.codigo;
                div.textContent = `${item.codigo} — ${item.producto} (Lote: ${item.lote})`;
                contResultados.appendChild(div);
            });
        }, 250);
    });

    // CLICK EN RESULTADO DE AUTOCOMPLETE
    contResultados.addEventListener("click", (e) => {
        if (!e.target.classList.contains("kardex-autocomplete-item")) return;

        const id = e.target.dataset.id;
        const codigo = e.target.dataset.codigo;

        hiddenPiezaId.value = id;
        inputBuscar.value = codigo;
        contResultados.innerHTML = "";

        cargarKardex(id);
    });

    async function cargarKardex(idPieza) {
        resumenPieza.innerHTML = "Cargando...";
        tbodyKardex.innerHTML = "";

        const res = await fetch(`/inventario/kardex/pieza/${idPieza}`);
        const data = await res.json();

        if (!data.success) {
            resumenPieza.innerHTML = `<p class="kardex-error">${data.message ?? 'Error al cargar kardex.'}</p>`;
            return;
        }

        // Resumen
        const p = data.pieza;

        resumenPieza.innerHTML = `
            <div><strong>Código pieza:</strong> ${p.codigo}</div>
            <div><strong>Producto:</strong> ${p.producto}</div>
            <div><strong>Código producto:</strong> ${p.codigo_producto}</div>
            <div><strong>Lote:</strong> ${p.lote}</div>
            <div><strong>Metros iniciales:</strong> ${p.metros_iniciales.toFixed(2)}</div>
            <div><strong>Libras iniciales:</strong> ${p.libras_iniciales.toFixed(2)}</div>
            <div><strong>Metros actuales:</strong> ${p.metros_actuales.toFixed(2)}</div>
            <div><strong>Libras actuales:</strong> ${p.libras_actuales.toFixed(2)}</div>
        `;

        // Movimientos
        tbodyKardex.innerHTML = "";

        if (!data.movimientos.length) {
            tbodyKardex.innerHTML = `<tr><td colspan="7">Sin movimientos registrados.</td></tr>`;
        } else {
            data.movimientos.forEach(mov => {
                const tr = document.createElement("tr");
                tr.innerHTML = `
                    <td>${mov.fecha}</td>
                    <td>${mov.origen}</td>
                    <td>${mov.tipo}</td>
                    <td>${mov.cantidad.toFixed(2)}</td>
                    <td>${mov.peso.toFixed(2)}</td>
                    <td>${mov.usuario}</td>
                    <td>${mov.comentario ?? ''}</td>
                `;
                tbodyKardex.appendChild(tr);
            });
        }

        // Activar botón PDF
        btnPdf.href = `${pdfBase}/${p.id_pieza}/pdf`;
        btnPdf.style.pointerEvents = "auto";
        btnPdf.style.opacity = "1";
    }
});
