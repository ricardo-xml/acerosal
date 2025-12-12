@extends('layouts.app')

@section('content')

<div class="inventario-wrapper">

    <h2>Inventario Manual</h2>

    {{-- Selección de Familia y Producto --}}
    <div class="form-grid grid-inventario">

        <div>
            <label>Familia</label>
            <select id="familia_select" class="form-control">
                <option value="">Seleccione</option>
                @foreach($familias as $f)
                    <option value="{{ $f->id_familia }}">{{ $f->descripcion }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label>Producto</label>
            <select id="producto_select" class="form-control" disabled></select>
        </div>

    </div>

    {{-- TARJETA DEL LOTE --}}
    <div id="tarjeta_lote_manual" style="display:none;">

        <div class="form-lote">

            <h4 id="titulo_producto">Producto seleccionado</h4>

            <div class="grid-lote">

                <div>
                    <label>Código de lote</label>
                    <input type="text"
                           id="codigo_lote"
                           class="codigo-lote form-control"
                           placeholder="Ej: L00012 (editable)">
                </div>

                <div>
                    <label>Fecha ingreso</label>
                    <input type="date"
                           id="fecha_ingreso"
                           class="form-control"
                           value="{{ date('Y-m-d') }}"
                           readonly>
                </div>

                <div>
                    <label>Total piezas</label>
                    <input type="text"
                           id="total_piezas"
                           class="form-control"
                           value="0"
                           readonly>
                </div>

                <div>
                    <label>Peso total (lb)</label>
                    <input type="number"
                           id="peso_total"
                           class="form-control"
                           step="0.01">
                </div>

                <div>
                    <label>Metros totales</label>
                    <input type="number"
                           id="mts_total"
                           class="form-control"
                           step="0.01">
                </div>

                <div>
                    <label>Relación lb/m</label>
                    <input type="text"
                           id="relacion"
                           class="form-control"
                           readonly>
                </div>

            </div> <!-- grid-lote -->

            <div class="tabla-piezas-container">

                <h5>Piezas del lote</h5>

                <table class="tabla-piezas">
                    <thead>
                        <tr>
                            <th>Código pieza</th>
                            <th>Metros inicial</th>
                            <th>Libras inicial</th>
                            <th>Acción</th>
                        </tr>
                    </thead>

                    <tbody id="tbody_piezas">
                    </tbody>

                    <tfoot>
                        <tr>
                            <th>Total</th>
                            <th id="totMet">0.00</th>
                            <th id="totLb">0.00</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>

                <button class="btn-agregar-pieza" id="btnAddPieza">
                    ➕ Agregar pieza
                </button>

            </div>

            <div style="margin-top:20px;">
                <button id="btnGuardar" type="button" class="btn-guardar" disabled>
                    Guardar Inventario Manual
                </button>
            </div>

        </div> {{-- form-lote --}}

    </div> {{-- tarjeta_lote_manual --}}

</div>

@vite(['resources/js/app.js'])

@endsection
