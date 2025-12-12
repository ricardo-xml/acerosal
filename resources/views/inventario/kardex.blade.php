@extends('layouts.app')

@section('content')
<div class="kardex-wrapper">

    {{-- BUSCADOR DE PIEZA --}}
    <section class="kardex-busqueda">
        <h2>Kardex de inventario</h2>

        <div class="kardex-busqueda-row">
            <label for="buscar_pieza">Código de pieza:</label>
            <div class="kardex-busqueda-input">
                <input type="text" id="buscar_pieza" autocomplete="off" placeholder="Ej: 304L-R-L00001-001">
                <input type="hidden" id="piezaSeleccionadaId">
                <div id="resultadosPiezas" class="kardex-autocomplete"></div>
            </div>
        </div>
    </section>

    {{-- RESUMEN DE LA PIEZA --}}
    <section class="kardex-resumen">
        <h3>Resumen de la pieza</h3>
        <div id="resumenPieza" class="kardex-resumen-card">
            <p class="kardex-placeholder">Busque y seleccione una pieza para ver su kardex.</p>
        </div>
    </section>

    {{-- TABLA DE MOVIMIENTOS --}}
    <section class="kardex-tabla">
        <div class="kardex-tabla-header">
            <h3>Movimientos</h3>
            <a href="#" id="btnExportarPdf" class="btn-guardar" target="_blank" style="pointer-events: none; opacity: 0.5;">
                Exportar PDF
            </a>
            <input type="hidden" id="kardexPdfBase" value="{{ url('/inventario/kardex/pieza') }}">
        </div>

        <table class="kardex-table">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Origen</th>
                    <th>Tipo</th>
                    <th>Metros</th>
                    <th>Libras</th>
                    <th>Usuario</th>
                    <th>Comentario</th>
                </tr>
            </thead>
            <tbody id="tbodyKardex">
            </tbody>
        </table>
    </section>

</div>

@vite(['resources/js/app.js'])
@endsection

