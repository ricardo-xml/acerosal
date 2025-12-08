@extends('layouts.app')

@section('content')

<h2 class="erp-title">Módulos Eliminados</h2>

<form method="GET" action="{{ route('modulos.admin') }}" class="erp-search-form">
    <div class="search-row">
        <input type="text" name="nombre" class="search-input" placeholder="Nombre"
               value="{{ request('nombre') }}">
        <button class="btn-primary" type="submit">
            <i class="fa-solid fa-magnifying-glass"></i> Buscar
        </button>
    </div>
</form>

<table class="erp-table">
    <thead>
        <tr>
            <th>#</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Módulo Padre</th>
            <th style="width:180px;text-align:center;">Acciones</th>
        </tr>
    </thead>

    <tbody>
        @forelse($modulos as $index => $m)
        <tr>
            <td>{{ ($modulos->currentPage() - 1) * $modulos->perPage() + $index + 1 }}</td>
            <td>{{ $m->nombre }}</td>
            <td>{{ $m->descripcion }}</td>
            <td>{{ optional($m->padre)->nombre ?? '-' }}</td>

            <td class="erp-actions-cell">

                <a href="{{ route('modulos.editar', $m->id_modulo) }}" class="btn-edit">
                    <i class="fa-solid fa-pen"></i>
                </a>

                {{-- Restaurar --}}
                <a href="{{ route('modulos.restaurar', $m->id_modulo) }}"
                   onclick="return confirm('¿Restaurar módulo?')"
                   class="btn-secondary"
                   style="background:#16a34a;">
                    <i class="fa-solid fa-arrow-rotate-left"></i>
                </a>

                {{-- Eliminar definitivo (opcional, futuro) --}}
                {{-- <a href="#" class="btn-delete"><i class="fa-solid fa-trash"></i></a> --}}

            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5" class="no-results">No hay registros eliminados</td>
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
