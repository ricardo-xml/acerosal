@extends('layouts.app')

@section('content')

<h2 class="erp-title">Lista de Módulos</h2>

<div class="erp-actions" style="margin-bottom: 10px;">
    <a href="{{ route('modulos.nuevo') }}" class="btn-primary">
        <i class="fa-solid fa-plus"></i> Nuevo Módulo
    </a>
</div>

<form method="GET" action="{{ route('modulos.lista') }}" class="erp-search-form">
    <div class="search-row">
        <input type="text"
               name="nombre"
               class="search-input"
               placeholder="Nombre"
               value="{{ request('nombre') }}">
        <button class="btn-primary" type="submit">
            <i class="fa-solid fa-magnifying-glass"></i> Buscar
        </button>
    </div>
</form>

<table class="erp-table">
    <thead>
        <tr>
            <th>#</th> {{-- Correlativo --}}
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Módulo Padre</th>
        </tr>
    </thead>

    <tbody>
        @forelse($modulos as $index => $m)
        <tr>
            {{-- correlativo usando número de página --}}
            <td>{{ ($modulos->currentPage() - 1) * $modulos->perPage() + $index + 1 }}</td>

            <td>{{ $m->nombre }}</td>
            <td>{{ $m->descripcion }}</td>
            <td>{{ optional($m->padre)->nombre ?? '-' }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="4" class="no-results">No hay registros</td>
        </tr>
        @endforelse
    </tbody>
</table>

@if($modulos->hasPages())
    <div style="margin-top:12px;">
        {{ $modulos->links() }}
    </div>
@endif

@endsection

