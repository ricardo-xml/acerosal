@extends('layouts.app')

@section('content')

<div class="erp-section">

    <h2 class="erp-title">
        <i class="fa-solid fa-coins"></i> Costos
    </h2>

    @if(session('msg'))
        <div class="form-alert">{{ session('msg') }}</div>
    @endif

    <div class="filter-box">
        <form method="GET" action="{{ route('costo.lista') }}">
            <label><strong>Nombre:</strong></label>
            <input type="text" name="nombre" value="{{ $nombre }}">
            <button class="btn-primary">Buscar</button>
            <a href="{{ route('costo.lista') }}" class="btn-secondary">Limpiar</a>
        </form>
    </div>

    <div class="erp-actions">
        <a class="btn-primary" href="{{ route('costo.nuevo') }}">➕ Nuevo Costo</a>
    </div>

    <p class="erp-total">Resultados: {{ $costos->total() }}</p>

    <table class="erp-table">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Descripción</th>
                <th style="width:120px; text-align:center;">Acciones</th>
            </tr>
        </thead>

        <tbody>
        @forelse($costos as $c)
            <tr>
                <td>{{ $c->nombre }}</td>
                <td>{{ $c->descripcion }}</td>
                <td class="erp-actions-cell">

                    <a class="btn-table btn-edit" href="{{ route('costo.editar', $c->id_costo) }}">
                        <i class="fa-solid fa-pen"></i>
                    </a>

                    <form action="{{ route('costo.eliminar', $c->id_costo) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn-table btn-delete" onclick="return confirm('¿Eliminar costo?')">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>

                </td>
            </tr>
        @empty
            <tr><td colspan="3" class="no-results">Sin resultados.</td></tr>
        @endforelse
        </tbody>
    </table>

    <div class="erp-pagination">
        {{ $costos->links() }}
    </div>

</div>

@endsection
