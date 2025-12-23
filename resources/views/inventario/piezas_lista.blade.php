@extends('layouts.app')

@section('content')
<div class="inventario-wrapper">
    <h2>Lista de Piezas (Ajustes)</h2>

    <form method="GET" class="form-grid grid-inventario" style="margin-bottom:15px;">
        <div>
            <label>Buscar</label>
            <input class="form-control" name="q" value="{{ request('q') }}" placeholder="Pieza / lote / producto">
        </div>

        <div>
            <label>Producto</label>
            <select class="form-control" name="id_producto">
                <option value="">Todos</option>
                @foreach($productos as $p)
                    <option value="{{ $p->id_producto }}" @selected((string)request('id_producto')===(string)$p->id_producto)>
                        {{ $p->codigo }} - {{ $p->descripcion }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label>Lote</label>
            <select class="form-control" name="id_lote">
                <option value="">Todos</option>
                @foreach($lotes as $l)
                    <option value="{{ $l->id_lote }}" @selected((string)request('id_lote')===(string)$l->id_lote)>
                        {{ $l->codigo }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label>Estado</label>
            <select class="form-control" name="estado">
                <option value="">Todos</option>
                @foreach(['Disponible','Retirada','Finalizada'] as $e)
                    <option value="{{ $e }}" @selected(request('estado')===$e)>{{ $e }}</option>
                @endforeach
            </select>
        </div>

        <div style="display:flex;gap:10px;align-items:end;">
            <button class="btn-guardar" type="submit">Filtrar</button>
            <a class="btn-guardar" href="{{ route('inventario.ajustes.piezas') }}">Limpiar</a>
        </div>
    </form>

    <table class="tabla-piezas">
        <thead>
        <tr>
            <th>#</th>
            <th>Código pieza</th>
            <th>Barcode</th>
            <th>Metros ini</th>
            <th>Libras ini</th>
            <th>Metros act</th>
            <th>Libras act</th>
            <th>Metros rec</th>
            <th>Libras rec</th>
            <th>Estado</th>
            <th>Acción</th>
        </tr>
        </thead>
        <tbody>
        @foreach($piezas as $pz)
            @php
                $estado = $pz->retirado ? 'Retirada' : ($pz->finalizado ? 'Finalizada' : 'Disponible');
            @endphp
            <tr>
                {{-- Correlativo --}}
                <td>{{ $loop->iteration }}</td>

                {{-- Código pieza --}}
                <td>{{ $pz->codigo }}</td>

                {{-- Barcode --}}
                <td>
                    {!! DNS1D::getBarcodeHTML($pz->codigo, 'C128', 1.5, 40) !!}
                </td>

                {{-- Numéricos --}}
                <td>{{ number_format($pz->cantidad_metros_inicial, 2) }}</td>
                <td>{{ number_format($pz->peso_libras_inicial, 2) }}</td>

                <td>{{ number_format($pz->cantidad_metros_actual, 2) }}</td>
                <td>{{ number_format($pz->peso_libras_actual, 2) }}</td>

                <td>{{ number_format($pz->cantidad_metros_recortados, 2) }}</td>
                <td>{{ number_format($pz->peso_libras_recortados, 2) }}</td>

                {{-- Estado --}}
                <td>{{ $estado }}</td>

                {{-- Acción --}}
                <td style="white-space:nowrap;">
                    <a class="btn-guardar"
                    href="{{ route('inventario.ajustes.show', $pz->id_lote) }}?pieza={{ $pz->id_pieza }}">
                    Ajustar
                    </a>

                    <a class="btn-guardar"
                    href="{{ route('inventario.ajustes.pieza.barcode_pdf', $pz->id_pieza) }}">
                    PDF
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>

    </table>

    <div style="margin-top:15px;">
        {{ $piezas->links() }}
    </div>
</div>
@endsection
