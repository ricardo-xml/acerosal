@extends('layouts.app')

@section('content')

<h2 class="erp-title">
    <i class="fa-solid fa-users-gear"></i> Gestión de Usuarios
</h2>

<div class="erp-actions" style="margin-bottom: 10px;">
    <a href="{{ route('usuarios.nuevo') }}" class="btn-primary">
        <i class="fa-solid fa-user-plus"></i> Nuevo Usuario
    </a>
</div>

<form method="GET" action="{{ route('usuarios.gestion') }}" class="erp-search-form">
    <div class="search-row">
        <input type="text"
               name="q"
               class="search-input"
               placeholder="Buscar por usuario, nombre, apellidos, correo o celular..."
               value="{{ request('q') }}">
        <button class="btn-primary" type="submit">
            <i class="fa-solid fa-magnifying-glass"></i> Buscar
        </button>
    </div>
</form>

<table class="erp-table">
    <thead>
        <tr>
            <th>#</th>
            <th>Usuario</th>
            <th>Nombre</th>
            <th>Apellidos</th>
            <th>Email</th>
            <th>Celular</th>
            <th>Acciones</th>
        </tr>
    </thead>

    <tbody>
        @forelse($usuarios as $index => $u)
        <tr>
            <td>{{ ($usuarios->currentPage() - 1) * $usuarios->perPage() + $index + 1 }}</td>

            <td>{{ $u->username }}</td>
            <td>{{ $u->nombre }}</td>
            <td>{{ $u->apellidos }}</td>
            <td>{{ $u->email }}</td>
            <td>{{ $u->celular }}</td>

            <td class="erp-actions-cell">
                <a href="{{ route('usuarios.editar', $u->id_usuario) }}" class="btn-table btn-edit">
                    <i class="fa-solid fa-pen-to-square"></i> Editar
                </a>

                <a href="{{ route('usuarios.eliminar', $u->id_usuario) }}"
                   class="btn-table btn-delete"
                   onclick="return confirm('¿Aplicar borrado lógico a este usuario?')">
                    <i class="fa-solid fa-trash-can"></i> Eliminar
                </a>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="no-results">No hay registros.</td>
        </tr>
        @endforelse
    </tbody>
</table>

@if($usuarios->hasPages())
    <div style="margin-top:12px;">
        {{ $usuarios->links() }}
    </div>
@endif

@endsection
