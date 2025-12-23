@extends('layouts.app')

@section('content')
<div class="inventario-wrapper">

    <h2>Ajustes de Inventario</h2>

    <div style="margin-bottom:10px;">
        <div><strong>{{ $lote->codigo_producto }}</strong></div>
        <div>{{ $lote->descripcion_producto }}</div>
    </div>

    <div id="alert_totales" class="alert" style="display:none; padding:10px; border:1px solid #c9a400; background:#fff7d6; margin-bottom:10px;">
        Advertencia: Los totales iniciales de las piezas no coinciden con el total del lote. (Se permite guardar.)
    </div>

    {{-- TARJETA DEL LOTE --}}
    <div id="tarjeta_lote_ajustes">

        <div class="form-lote">

            <h4>Lote: {{ $lote->codigo }}</h4>

            <div class="grid-lote">

                <div>
                    <label>Código de lote</label>
                    <input type="text" id="codigo_lote" class="codigo-lote form-control" value="{{ $lote->codigo }}">
                </div>

                <div>
                    <label>Fecha ingreso</label>
                    <input type="date" id="fecha_ingreso" class="form-control" value="{{ $lote->fecha_ingreso }}">
                </div>

                <div>
                    <label>Total piezas</label>
                    <input type="number" id="total_piezas" class="form-control" value="{{ $lote->total_piezas }}">
                </div>

                <div>
                    <label>Peso total (lb)</label>
                    <input type="number" id="peso_total_libras" class="form-control" step="0.01" value="{{ $lote->peso_total_libras }}">
                </div>

                <div>
                    <label>Metros totales</label>
                    <input type="number" id="cantidad_total_metros" class="form-control" step="0.01" value="{{ $lote->cantidad_total_metros }}">
                </div>

                <div>
                    <label>Relación lb/m</label>
                    <input type="number" id="relacion_cantidad_peso" class="form-control" step="0.0001" value="{{ $lote->relacion_cantidad_peso }}">
                </div>

                <div>
                    <label>Unidad peso</label>
                    <input type="text" id="unidad_medida_peso" class="form-control" value="{{ $lote->unidad_medida_peso }}">
                </div>

                <div>
                    <label>Unidad longitud</label>
                    <input type="text" id="unidad_medida_longitud" class="form-control" value="{{ $lote->unidad_medida_longitud }}">
                </div>

            </div>

            <div style="margin-top:15px; display:flex; gap:10px;">
                <button id="btnGuardarLote" type="button" class="btn-guardar">Guardar lote</button>
                <button id="btnEliminarLote" type="button" class="btn-guardar" style="background:#a30000;">Eliminar lote</button>
            </div>

            <hr style="margin:18px 0;">

            <div class="tabla-piezas-container">
                <h5>Piezas del lote</h5>

                <table class="tabla-piezas" id="tabla_piezas">
                    <thead>
                        <tr>
                            <th>Código pieza</th>
                            <th>Estado</th>
                            <th>Metros inicial</th>
                            <th>Libras inicial</th>
                            <th>Metros actual</th>
                            <th>Libras actual</th>
                            <th>Metros recortados</th>
                            <th>Libras recortados</th>
                            <th>Barcode</th>
                            <th>Acción</th>
                        </tr>
                    </thead>

                    <tbody id="tbody_piezas">
                        @foreach($piezas as $pz)
                            @php
                                $estado = $pz->retirado ? 'Retirada' : ($pz->finalizado ? 'Finalizada' : 'Disponible');
                                $highlight = ($id_pieza_resaltar && (int)$id_pieza_resaltar === (int)$pz->id_pieza);
                            @endphp
                            <tr data-id-pieza="{{ $pz->id_pieza }}" data-codigo="{{ $pz->codigo }}" class="{{ $highlight ? 'fila-resaltada' : '' }}">
                                <td>{{ $pz->codigo }}</td>
                                <td class="col-estado">{{ $estado }}</td>

                                <td><input class="form-control n-metros-inicial" type="number" step="0.01" value="{{ $pz->cantidad_metros_inicial }}"></td>
                                <td><input class="form-control n-libras-inicial" type="number" step="0.01" value="{{ $pz->peso_libras_inicial }}"></td>

                                <td><input class="form-control n-metros-actual" type="number" step="0.01" value="{{ $pz->cantidad_metros_actual }}"></td>
                                <td><input class="form-control n-libras-actual" type="number" step="0.01" value="{{ $pz->peso_libras_actual }}"></td>

                                <td><input class="form-control n-metros-rec" type="number" step="0.01" value="{{ $pz->cantidad_metros_recortados }}"></td>
                                <td><input class="form-control n-libras-rec" type="number" step="0.01" value="{{ $pz->peso_libras_recortados }}"></td>

                                <td class="barcode-cell" data-barcode-text="{{ $pz->codigo }}"></td>

                                <td style="display:flex;gap:8px;flex-wrap:wrap;">
                                    <button type="button" class="btn-guardar btnGuardarPieza">Guardar</button>
                                    <button type="button" class="btn-guardar btnRetirarPieza">Retirar</button>
                                    <button type="button" class="btn-guardar btnEliminarPieza" style="background:#a30000;">Eliminar</button>
                                    <a class="btn-guardar" href="{{ route('inventario.ajustes.pieza.barcode_pdf', $pz->id_pieza) }}">PDF</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                    <tfoot>
                        <tr>
                            <th colspan="2">Totales inicial</th>
                            <th id="totMetIni">0.00</th>
                            <th id="totLbIni">0.00</th>
                            <th colspan="6"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>

        </div>
    </div>

</div>

<script>
window.INV_AJUSTES = {
    idLote: {{ (int)$lote->id_lote }},
    routes: {
        updateLote: @json(route('inventario.ajustes.lote.update', $lote->id_lote)),
        eliminarLote: @json(route('inventario.ajustes.lote.eliminar', $lote->id_lote)),
        updatePieza: @json(route('inventario.ajustes.pieza.update', 0)),
        retirarPieza: @json(route('inventario.ajustes.pieza.retirar', 0)),
        eliminarPieza: @json(route('inventario.ajustes.pieza.eliminar', 0)),
    }
};
</script>

@vite([
    'resources/js/app.js',
    'resources/js/inventario-ajustes.js'
])

@endsection
