@extends('layouts.app')

@section('content')

<h2 class="erp-title"><i class="fa-solid fa-gears"></i> Gestión de Roles</h2>

<div class="erp-actions">
    <a href="{{ route('roles.nuevo') }}" class="btn-primary">Nuevo Rol</a>
</div>

<form method="GET" action="{{ route('roles.gestion') }}" class="filtro-form">
    <input type="text" name="nombre" placeholder="Nombre" value="{{ request('nombre') }}">
    <button class="btn-primary">Buscar</button>
</form>

<table class="erp-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($roles as $r)
        <tr>
            <td>{{ $r->id_rol }}</td>
            <td>{{ $r->nombre }}</td>
            <td>{{ $r->descripcion }}</td>
            <td>
                <a href="{{ route('roles.editar', $r->id_rol) }}" class="btn-secondary">Editar</a>
                <a href="{{ route('roles.eliminar', $r->id_rol) }}" class="btn-danger"
                    onclick="return confirm('¿Eliminar rol?')">Eliminar</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $roles->links() }}

@endsection
