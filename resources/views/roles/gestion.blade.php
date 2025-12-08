@extends('layouts.app')

@section('content')

<h2 class="erp-title">Gestión de Roles</h2>

<div class="erp-actions" style="margin-bottom: 10px;">
    <a href="{{ route('roles.nuevo') }}" class="btn-primary">
        <i class="fa-solid fa-plus"></i> Nuevo Rol
    </a>
</div>

<form method="GET" action="{{ route('roles.gestion') }}" class="erp-search-form">
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
            <th style="text-align:center;">Acciones</th>
        </tr>
    </thead>

    <tbody>
        @forelse($roles as $index => $r)
        <tr>
            <td>{{ ($roles->currentPage() - 1) * $roles->perPage() + $index + 1 }}</td>
            <td>{{ $r->nombre }}</td>
            <td>{{ $r->descripcion }}</td>

            <td class="erp-actions-cell">

                {{-- Ver detalle --}}
                <a href="{{ route('roles.detalle', $r->id_rol) }}" class="btn-table btn-edit">
                    <i class="fa-solid fa-eye"></i>
                </a>

                {{-- Editar --}}
                <a href="{{ route('roles.editar', $r->id_rol) }}" class="btn-table btn-edit">
                    <i class="fa-solid fa-pen"></i>
                </a>

                {{-- Eliminar (borrado lógico) --}}
                <a href="{{ route('roles.eliminar', $r->id_rol) }}"
                   class="btn-table btn-delete"
                   onclick="return confirm('¿Eliminar este rol?')">
                    <i class="fa-solid fa-trash"></i>
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
