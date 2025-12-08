@extends('layouts.app')

@section('content')

<h2 class="erp-title">Lista de Roles</h2>

<div class="erp-actions" style="margin-bottom: 10px;">
    <a href="{{ route('roles.nuevo') }}" class="btn-primary">
        <i class="fa-solid fa-plus"></i> Nuevo Rol
    </a>
</div>

<form method="GET" action="{{ route('roles.lista') }}" class="erp-search-form">
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
            <th>#</th> {{-- correlativo --}}
            <th>Nombre</th>
            <th>Descripción</th>
            <th style="width:100px;"></th>
        </tr>
    </thead>

    <tbody>
        @forelse($roles as $index => $r)
        <tr>
            {{-- correlativo por página --}}
            <td>{{ ($roles->currentPage() - 1) * $roles->perPage() + $index + 1 }}</td>

            <td>{{ $r->nombre }}</td>
            <td>{{ $r->descripcion }}</td>

            <td class="erp-actions-cell">
                <a href="{{ route('roles.detalle', $r->id_rol) }}" class="btn-table btn-edit">
                    <i class="fa-solid fa-eye"></i>
                </a>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="4" class="no-results">No hay registros</td>
        </tr>
        @endforelse
    </tbody>
</table>

@if($roles->hasPages())
    <div style="margin-top:12px;">
        {{ $roles->links() }}
    </div>
@endif

@endsection
