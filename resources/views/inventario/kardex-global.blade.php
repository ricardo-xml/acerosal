@extends('layouts.app')

@section('content')
<div class="kardex-global-wrapper">

    <h2>Kardex Global de Inventario</h2>

    {{-- FILTROS --}}
    <section class="filtros-kardex-global">

        <div>
            <label>Fecha desde:</label>
            <input type="date" id="f_desde">
        </div>

        <div>
            <label>Fecha hasta:</label>
            <input type="date" id="f_hasta">
        </div>

        <div>
            <label>Producto:</label>
            <select id="f_producto">
                <option value="">Todos</option>
                @foreach($productos as $prod)
                    <option value="{{ $prod->id_producto }}">{{ $prod->descripcion }}</option>
                @endforeach
            </select>
        </div>

        <div class="autocomplete-wrapper">
            <label>Código pieza:</label>
            <input type="text" id="f_codigo" autocomplete="off">
            <div id="autoCodigo" class="autocomplete-list"></div>
        </div>

        <div>
            <label>Tipo:</label>
            <select id="f_tipo">
                <option value="">Todos</option>
                <option value="entrada">Entrada</option>
                <option value="salida">Salida</option>
            </select>
        </div>

        <div>
            <label>Origen:</label>
            <select id="f_origen">
                <option value="">Todos</option>
                <option>Compra</option>
                <option>Manual</option>
                <option>Corte</option>
                <option>Ajuste</option>
            </select>
        </div>

        <div>
            <label>Usuario:</label>
            <select id="f_usuario">
                <option value="">Todos</option>
                @foreach($usuarios as $u)
                    <option value="{{ $u->id_usuario }}">{{ $u->nombre }}</option>
                @endforeach
            </select>
        </div>

        <button id="btnFiltrar" class="btn-guardar">Aplicar filtros</button>

        <a id="btnPdfGlobal" class="btn-guardar" target="_blank"
           style="opacity:0.5;pointer-events:none;">
            Exportar PDF
        </a>

    </section>

    {{-- TABLA --}}
    <table class="tabla-kardex-global">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Producto</th>
                <th>Pieza</th>
                <th>Lote</th>
                <th>Origen</th>
                <th>Tipo</th>

                {{-- DELTAS --}}
                <th>Δ Mts</th>
                <th>Δ Lbs</th>

                {{-- SALDOS --}}
                <th>Saldo Mts</th>
                <th>Saldo Lbs</th>

                <th>Usuario</th>
                <th>Comentario</th>
                <th>Kardex</th>
            </tr>
        </thead>
        <tbody id="tbodyGlobal"></tbody>
    </table>

</div>

@vite(['resources/js/app.js'])
@endsection
