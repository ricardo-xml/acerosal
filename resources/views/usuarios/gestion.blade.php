@extends('layouts.app')

@section('content')

<div class="erp-section">

    <h2 class="erp-title">
        <i class="fa-solid fa-user-gear"></i> Gestión de Usuarios
    </h2>

    @if(session('msg'))
        <div class="form-alert">{{ session('msg') }}</div>
    @endif

    {{-- FILTROS --}}
    <form method="GET" action="{{ route('usuarios.gestion') }}" class="erp-filters">
        <div class="form-grid">

            <div class="form-group">
                <label>Nombre</label>
                <input type="text" name="nombre" class="form-control" value="{{ request('nombre') }}">
            </div>

            <div class="form-group">
                <label>Usuario</label>
                <input type="text" name="username" class="form-control" value="{{ request('username') }}">
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="text" name="email" class="form-control" value="{{ request('email') }}">
            </div>

        </div>

        <div class="form-actions">
            <button class="btn-primary">Aplicar Filtros</button>
            <a href="{{ route('usuarios.gestion') }}" class="btn-secondary">Limpiar</a>
        </div>
    </form>

    {{-- BOTÓN NUEVO --}}
    <div class="form-actions" style="margin-bottom: 10px;">
        <a class="btn-primary" href="{{ route('usuarios.nuevo') }}">
            <i class="fa-solid fa-user-plus"></i> Nuevo Usuario
        </a>
    </div>

    {{-- TABLA --}}
    <table class="erp-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Nombre Completo</th>
                <th>Email</th>
                <th>Celular</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
            @forelse($usuarios as $u)
                <tr>
                    <td>{{ $u->id_usuario }}</td>
                    <td>{{ $u->username }}</td>
                    <td>{{ $u->nombre }} {{ $u->apellidos }}</td>
                    <td>{{ $u->email }}</td>
                    <td>{{ $u->celular }}</td>

                    <td class="erp-actions">

                        {{-- EDITAR --}}
                        <a class="btn-edit" href="{{ route('usuarios.editar', $u->id_usuario) }}">✏️</a>

                        {{-- ELIMINAR --}}
                        <a class="btn-delete"
                           href="{{ route('usuarios.eliminar', $u->id_usuario) }}"
                           onclick="return confirm('¿Eliminar usuario? (borrado lógico)')">
                            🗑️
                        </a>

                        {{-- ASIGNAR ROLES --}}
                        <a class="btn-secondary"
                           href="{{ route('usuarios.roles', $u->id_usuario) }}">
                            🔐 Roles
                        </a>

                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">Sin resultados.</td>
                </tr>
            @endforelse
        </tbody>

    </table>

    {{ $usuarios->links() }}

</div>

@endsection
