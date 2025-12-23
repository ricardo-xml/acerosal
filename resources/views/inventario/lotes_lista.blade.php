@extends('layouts.app')

@section('content')
<div class="inventario-wrapper">
    <h2>Lista de Lotes (Ajustes)</h2>

    <form method="GET" class="form-grid grid-inventario" style="margin-bottom:15px;">
        <div>
            <label>Buscar</label>
            <input class="form-control" name="q" value="{{ request('q') }}" placeholder="Lote / producto / descripción">
        </div>

        <div>
            <label>Estado</label>
            <select class="form-control" name="estado">
                <option value="">Todos</option>
                @foreach(['Disponible','Parcial','Agotado'] as $e)
                    <option value="{{ $e }}" @selected(request('estado')===$e)>{{ $e }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label>Familia</label>
            <select class="form-control" name="id_familia">
                <option value="">Todas</option>
                @foreach($familias as $f)
                    <option value="{{ $f->id_familia }}" @selected((string)request('id_familia')===(string)$f->id_familia)>
                        {{ $f->descripcion }}
                    </option>
                @endforeach
            </select>
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
            <label>Desde</label>
            <input type="date" class="form-control" name="desde" value="{{ request('desde') }}">
        </div>

        <div>
            <label>Hasta</label>
            <input type="date" class="form-control" name="hasta" value="{{ request('hasta') }}">
        </div>

        <div style="display:flex;gap:10px;align-items:end;">
            <button class="btn-guardar" type="submit">Filtrar</button>
            <a class="btn-guardar" href="{{ route('inventario.ajustes.lotes') }}">Limpiar</a>
        </div>
    </form>

    <table class="tabla-piezas">
        <thead>
        <tr>
            <th>Lote</th>
            <th>Producto</th>
            <th>Estado</th>
            <th>Disponible (lb)</th>
            <th>Disponible (m)</th>
            <th>Ingreso</th>
            <th>Acción</th>
        </tr>
        </thead>
        <tbody>
        @foreach($lotes as $l)
            <tr>
                <td>{{ $l->codigo_lote }}</td>
                <td><strong>{{ $l->codigo_producto }}</strong> - {{ $l->descripcion_producto }}</td>
                <td>{{ $l->estado_lote }}</td>
                <td>{{ number_format((float)$l->peso_disponible_libras, 2) }}</td>
                <td>{{ number_format((float)$l->metros_disponibles, 2) }}</td>
                <td>{{ $l->fecha_ingreso }}</td>
                <td>
                    <a class="btn-guardar" href="{{ route('inventario.ajustes.show', $l->id_lote) }}">Ajustar</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div style="margin-top:15px;">
        {{ $lotes->links() }}
    </div>
</div>
@endsection
