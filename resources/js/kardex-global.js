document.addEventListener("DOMContentLoaded", () => {

    const tbody = document.getElementById("tbodyGlobal");
    const btnFiltrar = document.getElementById("btnFiltrar");
    const btnPdf = document.getElementById("btnPdfGlobal");

    function getFiltros() {
        return {
            fecha_desde: document.getElementById("f_desde").value,
            fecha_hasta: document.getElementById("f_hasta").value,
            producto: document.getElementById("f_producto").value,
            codigo_pieza: document.getElementById("f_codigo").value,
            tipo: document.getElementById("f_tipo").value,
            origen: document.getElementById("f_origen").value,
            usuario: document.getElementById("f_usuario").value
        };
    }

    async function cargarDatos() {
        const filtros = getFiltros();
        const params = new URLSearchParams(filtros).toString();

        const res = await fetch(`/inventario/kardex-global/datos?${params}`);
        const data = await res.json();

        tbody.innerHTML = "";

        // ⚠️ Ajuste de colspan (ahora son más columnas)
        if (!data.length) {
            tbody.innerHTML = `<tr><td colspan="13">No hay movimientos.</td></tr>`;
            btnPdf.style.pointerEvents = "none";
            btnPdf.style.opacity = "0.5";
            return;
        }

        data.forEach(m => {
            const tr = document.createElement("tr");

            tr.innerHTML = `
                <td>${m.fecha}</td>
                <td>${m.producto}</td>
                <td>${m.codigo}</td>
                <td>${m.lote}</td>
                <td>${m.origen}</td>
                <td>${m.tipo}</td>

                <!-- DELTAS -->
                <td class="text-end">${Number(m.mts).toFixed(2)}</td>
                <td class="text-end">${Number(m.lbs).toFixed(2)}</td>

                <!-- SALDOS -->
                <td class="text-end fw-bold">${Number(m.saldo_mts).toFixed(2)}</td>
                <td class="text-end fw-bold">${Number(m.saldo_lbs).toFixed(2)}</td>

                <td>${m.usuario}</td>
                <td>${m.comentario ?? ''}</td>
                <td>
                    <a href="/inventario/kardex/pieza/${m.id_pieza}" class="btn-mini">Ver</a>
                </td>
            `;

            tbody.appendChild(tr);
        });

        btnPdf.href = `/inventario/kardex-global/pdf?${params}`;
        btnPdf.style.pointerEvents = "auto";
        btnPdf.style.opacity = "1";
    }

    btnFiltrar.addEventListener("click", cargarDatos);

    cargarDatos(); // carga inicial
});
