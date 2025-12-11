@extends('layouts.app')

@section('content')

<h2 class="erp-title">
    <i class="fa-solid fa-user-shield"></i> Administración de Usuarios
</h2>

<form method="GET" action="{{ route('usuarios.admin') }}" class="erp-search-form">
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
            <th>Estado</th>
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
            <td>{{ $u->inactivo ? 'Eliminado' : 'Activo' }}</td>

            <td class="erp-actions-cell">
                <a href="{{ route('usuarios.detalle', $u->id_usuario) }}" class="btn-table btn-edit">
                    <i class="fa-solid fa-eye"></i> Detalle
                </a>

                @if(!$u->inactivo)
                    <a href="{{ route('usuarios.eliminar', $u->id_usuario) }}"
                       class="btn-table btn-delete"
                       onclick="return confirm('¿Aplicar borrado lógico a este usuario?')">
                        <i class="fa-solid fa-trash-can"></i> Eliminar
                    </a>
                @else
                    <a href="{{ route('usuarios.restaurar', $u->id_usuario) }}"
                       class="btn-table btn-edit"
                       onclick="return confirm('¿Restaurar este usuario?')">
                        <i class="fa-solid fa-rotate-left"></i> Restaurar
                    </a>
                @endif
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="8" class="no-results">No hay registros.</td>
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
